/**
 * FormSave
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of FormSave.
 *
 * FormSave is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * FormSave is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormSave; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package FormSave
 */
 
var fsPageStores = Ext.extend(Ext.Panel, {
	initComponent: function() {		
		// Rightclick mouse menu for the grid
		this.rowMenu = new Ext.menu.Menu({
			baseParams: {
				rowId: 0
			},
			items: [
				{
					text: _('formsave.viewdata'),
					listeners: {
						click: {
							scope: this,
							fn: function() {
								fsCore.ajax.request({
									url: fsCore.config.connectorUrl,
									params: {
										id: this.rowMenu.baseParams.rowId,
										action: 'mgr/form/getdata'
									},
									scope: this,
									success: function(response) {
										this.formWindow.show();
										Ext.getCmp('form-data-view').update(response.responseText);
									}
								});
							}	
						}	
					} 
				},
				{
					text: _('formsave.delete'),
					listeners: {
						click: {
							scope: this,
							fn: function() {
								Ext.Msg.show({
									title: _('formsave.delete_confirm_title'),
									msg: _('formsave.delete_confirm_msg'),
									buttons: Ext.Msg.YESNO,
									fn: function(response) {
										if (response == 'yes') {
											fsCore.ajax.request({
												url: fsCore.config.connectorUrl,
												params: {
													id: this.rowMenu.baseParams.rowId,
													action: 'mgr/form/delete'
												},
												scope: this,
												success: function(response) {
													fsCore.stores.forms.load();
												}
											});
										}
									},
									icon: Ext.MessageBox.QUESTION,
									scope: this
								});
							}	
						}	
					}
				}
			]
		});
		
		this.formGrid = new Ext.grid.GridPanel({
			store: fsCore.stores.forms,
			autoHeight: true,
			loadMask: true,
			viewConfig: {
				forceFit: true,
				enableRowBody: true,
				autoFill: true,
				deferEmptyText: false,
				showPreview: true,
				scrollOffset: 0,
				emptyText: _('ext_emptymsg'),
				sm: new Ext.grid.RowSelectionModel({
					singleSelect: true
				})
			},
			bbar: new Ext.PagingToolbar({
				store: fsCore.stores.forms,
				displayInfo: true,
				pageSize: 25,
				perpendButtons: true
			}),
			loadMask: true,
			enableDragDrop: true,
		    autoExpandColumn: 'form-data',
		    columns: [
				{
		            xtype: 'gridcolumn',
		            dataIndex: 'id',
		            header: _('formsave.form_id'),
					width: 100
		        },
				{
		            xtype: 'gridcolumn', 
		            dataIndex: 'topic',
		            header: _('formsave.form_topic')
		        },
				{
		            xtype: 'gridcolumn',
		            dataIndex: 'data_intro',
					id: 'form-data',
		            header: _('formsave.form_data_intro') 
		        },
		    	{
		            xtype: 'gridcolumn',
		            dataIndex: 'time',
		            width: 50,
		            header: _('formsave.form_time'),
		            renderer: function(value) {
		            	var formDate = Date.parseDate(value, 'U');
		            	return formDate.format('Y/m/d H:i');
		            }
		        }
		    ], 
		    listeners: {
				added: {
		    		scope: this,
		    		fn: function() {
		        		this.formGrid.getStore().load();
						fsCore.stores.forms.load();
		        	}
		    	},
		    	rowContextMenu: {
			    	scope: this,
		    		fn: function(grid, rowIndex, event) {
		    			// Set the database ID in the menu's base params so we can access it when an action is performed
		    			this.rowMenu.baseParams.rowId = fsCore.stores.forms.getAt(rowIndex).get('id');
		    			this.rowMenu.showAt(event.xy);
		    			event.stopEvent();
		    		}
				},
				render: {
					scope: this,
					fn: function(grid) {
						
					}
				}
			}
		});
		
		this.formWindow = new Ext.Window({
			padding: 10,
			title: _('formsave.form_data'),
			width: 650,
			y: 25,
			modal: true,
			autoHeight: true,
			closeAction: 'hide',
			items: [
				{
					xtype: 'panel',
					id: 'form-data-view',
					plain: true,
					border: false,
					html: ''	
				}
			],
			bbar: [
				'->',
				{
					xtype: 'button',
					text: _('formsave.close'),
					scope: this,
					handler: function() {
						this.formWindow.hide();
					}
				}
			],
			listeners: {
				show: function() {
					
				}
			}
		});
			
		// The mainpanel always has to be in the "this.mainPanel" variable
		this.mainPanel = new Ext.Panel({
			renderTo: 'formsave-content',
			padding: 15,
			border: false,
			items: [
				{
					xtype: 'form',
					border: false,
					labelWidth: 175,
					style: {
						marginBottom: '15px'
					},
					bbar: [
						{
							xtype: 'label',
							html: '<div style="width: 177px;">&nbsp;</div>'
						},
						{
							xtype: 'button',
							text: _('formsave.clear'),
							scope: this,
							handler: function() {
								Ext.getCmp('formtopic').setValue('*');
								Ext.getCmp('startdate').setValue('');
								Ext.getCmp('enddate').setValue('');
								
								fsCore.stores.forms.baseParams.topic = '';
								fsCore.stores.forms.baseParams.startDate = '';
								fsCore.stores.forms.baseParams.endDate = '';
								fsCore.stores.forms.load();
							}
						},
						{
							xtype: 'button',
							text: _('formsave.export'),
							scope: this,
							handler: function() {
								var topic = Ext.getCmp('formtopic').getValue();
								var startDate = Ext.getCmp('startdate').getValue();
								var endDate = Ext.getCmp('enddate').getValue();
								var template = Ext.getCmp('formtemplate').getValue();
								
								window.location = fsCore.config.connectorUrl+'?HTTP_MODAUTH='+siteId+'&action=mgr/form/export&topic='+encodeURIComponent(topic)+'&startDate='+fsCore.stores.forms.baseParams.startDate+'&endDate='+fsCore.stores.forms.baseParams.endDate+'&template='+encodeURIComponent(template);
							}
						},
						'->',
						{
							xtype: 'button',
							text: _('formsave.about'),
							handler: function() {
								new Ext.Window({
									title: _('formsave.about'),
									modal: true,
									html: '<iframe width="630" height="470" frameborder="0" src="'+fsCore.config.connectorUrl+'?action=mgr/about&HTTP_MODAUTH='+siteId+'"></iframe>',
									width: 640,
									height: 480,  
									padding: 10
								}).show();
							}
						}
					],
					items: [
						{
							xtype: 'combo',
							displayField: 'topic',
							valueField: 'value',
							width: 200,
							forceSelection: true,
							store: fsCore.stores.formtopics,
							mode: 'remote',
							value: '*',
							triggerAction: 'all',
							fieldLabel: _('formsave.choose_topic'),
							name: 'topic',
							id: 'formtopic',
							listeners: {
								select: {
									scope: this,
									fn: function(combo, record) {
										fsCore.stores.forms.baseParams.topic = record.get('value');
										fsCore.stores.forms.load();
									}
								}
							}
						},
						{
							xtype: 'datefield',
							fieldLabel: _('formsave.select_start_date'),
							id: 'startdate',
							listeners: {
								select: {
									scope: this,
									fn: function(dateField, dateObject) {
										fsCore.stores.forms.baseParams.startDate = dateObject.format('d-m-Y');
										fsCore.stores.forms.load();
									}
								}
							}
						},
						{
							xtype: 'datefield',
							fieldLabel: _('formsave.select_end_date'),
							id: 'enddate',
							listeners: {
								select: {
									scope: this,
									fn: function(dateField, dateObject) {
										fsCore.stores.forms.baseParams.endDate = dateObject.format('d-m-Y');
										fsCore.stores.forms.load();
									}
								}
							}
						},
						{
							xtype: 'compositefield',
							fieldLabel: _('formsave.choose_format'),
							items: [
								{
									xtype: 'combo',
									displayField: 'template',
									valueField: 'template',
									id: 'formtemplate',
									width: 200,
									forceSelection: true,
									store: fsCore.stores.templates,
									mode: 'remote',
									value: 'csv',
									triggerAction: 'all',
									name: 'template',
									id: 'formtemplate',
									listeners: {
										select: function(combo, record) {
											Ext.get('fs-help-text').update(record.get('helpText'));
										}
									}
								},
								{
									type: 'label',
									plain: true,
									border: false,
									html: '<div class="fs-help-text" id="fs-help-text">'+_('formsave.help_csv')+'</div>'
								}
							]
						}
					]			
				},
				this.formGrid
			]
		});
	}
});

Ext.onReady(function() {
	// Set page title and load main panel
	fsCore.setTitle(_('formsave.view_forms'));
		
	// this makes the main class accessible through fsCore.pageClass and the panel through fsCore.pagePanel
	fsCore.loadPanel(fsPageStores); 
});