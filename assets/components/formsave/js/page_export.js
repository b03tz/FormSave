/**
 * UserExport
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of UserExport.
 *
 * UserExport is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UserExport is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UserExport; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package UserExport
 */
 
var uePageStores = Ext.extend(Ext.Panel, {
	initComponent: function() {		
		this.userGrid = new Ext.grid.GridPanel({
			store: ueCore.stores.users,
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
	            pageSize: 25,
	            store: ueCore.stores.users,
	            displayInfo: true,
	            displayMsg: 'Displaying users {0} - {1} of {2}',
	            emptyMsg: 'No users to display'
	        }),
			loadMask: true,
			ddGroup: 'storeGridDD',
			enableDragDrop: true,
		    autoExpandColumn: 'store-description-column',
		    columns: [
				{
		            xtype: 'gridcolumn',
		            dataIndex: 'id',
		            header: 'User ID',
					width: 100
		        },
		        {
		        	xtype: 'gridcolumn',
		        	dataIndex: 'username',
		        	header: 'Username'
		        }
		    ], 
		    listeners: {
				added: {
		    		scope: this,
		    		fn: function() {
						ueCore.stores.users.load();
		        	}
		    	}
			}
		});
			
		// The mainpanel always has to be in the "this.mainPanel" variable
		this.mainPanel = new Ext.Panel({
			renderTo: 'userexport-content',
			padding: 15,
			border: false,
			tbar: [
			
			],
			items: [
				{
					xtype: 'container',
					layout: 'column',
					items: [
						{
							xtype: 'container',
							layout: 'column',
							items: [
								{
									html: '<span style="height: 35px; line-height: 35px; font-weight: bold;">Add new filter:</span>',
									border: false,
									plain: true
								},
								{
									xtype: 'combo',
									displayField: 'option',
									valueField: 'value',
									anchor: '100%',
									fieldLabel: 'Add new filter',
									forceSelection: true,
									store: ueCore.stores.filters,
									mode: 'remote',
									triggerAction: 'all',
									name: 'filter_field',
									allowBlank: false,
									width: 180,
									id: 'query-field'
								},
								{
									xtype: 'combo',
									displayField: 'option',
									valueField: 'value',
									anchor: '100%',
									width: 130,
									forceSelection: true,
									mode: 'local',
									store: new Ext.data.SimpleStore({
										fields: [
											'option', 'value'
										],
										data: [
											['Equals', '='],
											['Not equals', '!='],
											['Greater than', '>'],
											['Less than', '<']
										]
									}),
									triggerAction: 'all',
									name: 'filter_andor',
									allowBlank: false,
									id: 'query-option'
								},
								{
									xtype: 'textfield',
									fieldName: 'query',
									id: 'query-where'
								}
							]
						},
						{
							xtype: 'button',
							style: {
								marginTop: '3px'
							},
							text: '+ Add',
							scope: this,
							handler: function() {
								var field = Ext.getCmp('query-field').getValue();
								var option = Ext.getCmp('query-option').getValue();
								var where = Ext.getCmp('query-where').getValue();
								
								if (field == '' || option == '') {
									alert('Please enter on which field to filter and what filter option to use');
								} else {
									var currentQuery = Ext.get('user-query').dom.value;

									if (currentQuery == '') {
										var newQuery = field+'||'+option+'||'+where;
									} else {
										var newQuery = currentQuery+"\n"+field+'||'+option+'||'+where;
									}
									
									Ext.get('user-query').dom.value = newQuery;
									
									ueCore.stores.users.baseParams.query = newQuery; 
									this.userGrid.getStore().load();
								}
							}
						}
					]
				},
				{
					html: '<br />',
					border: false,
					plain: true
				},
				{
					html: '<b>Current query:</b><br /><textarea rows="4" cols="80" id="user-query" class="user-query" onblur="ueCore.stores.users.baseParams.query = this.value; ueCore.stores.users.load();"></textarea>',
					border: false,
					plain: true
				},
				{
					layout: 'column',
					plain: true,
					border: false,
					items: [
						{
							xtype: 'button',
							scope: this,
							text: 'Clear query',
							handler: function() {
								Ext.get('user-query').dom.value = '';
								ueCore.stores.users.baseParams.query = ''; 
								this.userGrid.getStore().load();
							}
						},
						{
							xtype: 'button',
							scope: this,
							text: 'Export to CSV',
							handler: function() {
								var idString = '';
								// Create user ID string
								this.userGrid.getStore().each(function(record) {
									idString = idString+record.get('id')+',';
								});
								
								// Start the export
								window.location = ueCore.config.connectorUrl+'?action=mgr/exportusers&users='+idString+'&HTTP_MODAUTH='+siteId;
							}
						}
					]
				},
				{
					html: '<br /><br />',
					border: false,
					plain: true
				},
				this.userGrid
			]
		});
	}
});

Ext.onReady(function() {
	// Set page title and load main panel
	ueCore.setTitle('User export');
		
	// this makes the main class accessible through ueCore.pageClass and the panel through ueCore.pagePanel
	ueCore.loadPanel(uePageStores); 
});