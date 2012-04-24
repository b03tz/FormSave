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

var FormSave = function(config) {
    config = config || {};
    FormSave.superclass.constructor.call(this,config);
};

Ext.extend(FormSave, Ext.Component, {
	initComponent: function() {
		this.loadMask = new Ext.LoadMask(Ext.getBody(), {msg: 'Loading, please wait...'});
		this.messageWindow = null;
		this.siteId = siteId;
		this.pagePanel = null;
		this.pageClass = null; 
		this.stores = {};
		this.config = {};
		this.tasks = {};
		this.toolTips = {};
		
		this.config.disableMask = false;
		
		Ext.onReady(function() {
			if (Ext.get('formsave-container')) {
				this.mainPanel = new Ext.Panel({
					renderTo: 'modx-panel-holder',
					border: false,
					autoHeight: true,
					unstyled: true,
					padding: 0,
					margin: 0,
					baseCls: 'fs-mainpanel',
					items: [
						{	
							html: '<h2 id="formsave-title"></h2>',
							border: false,
							cls: 'modx-page-header'	
						},
						{
							id: 'formsave-content',
							border: false,
							padding: '0px',
							margin: '0px',
							baseCls: 'formsave-content',
							plain: true,
							unstyled: true
						}
					]
				});
			}
		}, this);
		
		this.ajax = new Ext.data.Connection({
			disableCaching: true,
			extraParams: {
				HTTP_MODAUTH: this.siteId
			}
		});
		
		this.ajax.on('beforerequest', function() {
			if (!this.config.disableMask) {
				this.showAjaxLoader();
			}
		}, this);
		
		this.ajax.on('requestcomplete', function() {
			if (!this.config.disableMask) {
				this.hideAjaxLoader();
			}
		}, this);
		
		this.ajax.on('requestexception', function() {
			if (!this.config.disableMask) {
				this.hideAjaxLoader();
			}
		}, this);
	},
	getToolTip: function(key) {
		if (key in this.toolTips) {
			return this.toolTips[key];
		} 
		
		return false;
	},
	addToolTip: function(key, element, toolTip) {
		item = Ext.get(element); 
		
		if (!toolTip) {
			var toolTip = item.getAttribute('data-qtip');
		}
		
		this.toolTips[key] = new Ext.ToolTip({
			target: item,
			anchor: 'right',
			constrainPosition: false,
			showDelay: 0,
			hideDelay: 0,
			html: toolTip
		});
	},
	showMessage: function(message, messageStay) {
		if (!this.messageWindow || this.messageWindow == null) {
			this.messageWindow = new Ext.Window({
				closable: false,
				resizable: false,
				unstyled: true,
				shadow: false,
				y: -50,
				bodyStyle: {
					backgroundColor: '#FFFFFF',
					padding: '10px',
					border: '2px solid #666666',
					borderRadius: '10px',
					'-moz-border-radius': '10px',
					'-webkit-border-radius': '10px',
					fontWeight: 'bold',
					zIndex: 999999
				}
			});
			
			this.messageWindow.show();
			this.messageWindowEl = this.messageWindow.getEl();
			
			this.tasks.hideMessage = new Ext.util.DelayedTask(function() {
				this.hideMessage();
			}, this);
			
			Ext.getBody().appendChild(this.messageWindowEl);
		}
		
		// Set styles
		Ext.get(this.messageWindow.getId()).setStyle('z-index', '999999');
		this.messageWindowEl.setOpacity(0);
		this.messageWindowEl.setOpacity(1, true);
		this.messageWindowEl.setY(10, true);
		
		// Update message
		this.messageWindow.update(message);
				
		// Hide after 3 seconds
		if (!messageStay) {
			this.tasks.hideMessage.delay(3000);
		}
	},
	hideMessage: function() {
		this.messageWindowEl.setY(-50, true);
		this.messageWindowEl.setOpacity(0, true);
	},
	showAjaxLoader: function() {
		// Commented out because chrome has a bug with ExtJS
		//this.loadMask.show();
		this.showMessage('<div class="fs-ajax-loading">'+_('formsave.ajax_loading')+'</div>', true);
		
		if (!Ext.get('fc-ajax-haze')) {
			Ext.getBody().createChild({
				tag: 'div',
				id: 'fs-ajax-haze',
				'class': 'fs-ajax-haze',
				style: {
					position: 'absolute',
					top: '0px',
					left: '0px'
				}
			});
		}
		
		if (Ext.get('fs-ajax-haze')) {
			var windowSize = getWindowScrollSize();
			Ext.get('fs-ajax-haze').setStyle({
				display: 'block',
				opacity: 0.5
			});
			
			Ext.getBody().setStyle({
				overflow: 'hidden'
			});
		}
	},
	hideAjaxLoader: function() {
		// Commented out because chrome has a bug with ExtJS
		this.hideMessage();
		
		if (Ext.get('fs-ajax-haze')) {
			Ext.get('fs-ajax-haze').setStyle({
				display: 'none'
			});
			
			Ext.getBody().setStyle({
				overflow: 'visible'
			});
		}
	},
	loadPanel: function(panelClass) {
		this.pageClass = new panelClass();
		this.pagePanel = this.pageClass.mainPanel;
	},
	setTitle: function(title) {
		Ext.get('formsave-title').update('<div style="margin-top: 14px;">'+title+'</div>');
	},
	getUrlVar: function(key) {
		// Thanks to: http://snipplr.com/users/Roshambo/
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        if (hash[0] == key) {
	        	return hash[1];	 
	        }
	    }
	    
	    return '';
	},
	createAlias: function(string) {
		string = string.toLowerCase();
		var allowed = 'abcdefghijklmnopqrstuvwxyz0123456789';
		var output = '';
		for(var i = 0; i < string.length; i++) {
			if (allowed.indexOf(string[i]) != -1) {
				output += string[i];
			} else {
				output += '-';	
			}
		}	
		
		while(output.indexOf('--') != -1) {
			output = output.replace(/--/gi, '-', output);	
		}
		
		if (output.substr(0, 1) == '-') {
			output = output.substr(1);	
		}
		
		if (output.substr(-1) == '-') {
			output = output.substr(0, (output.length)-1);	
		}
		
		return output;
	}
});

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.clientHeight,
        window.innerHeight,
        document.documentElement.clientHeight
    );
}

function getWindowScrollSize() {
    var D = document;
    var y = Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
    
    var x = Math.max(
        Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
        Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
        Math.max(D.body.clientWidth, D.documentElement.clientWidth)
    );
    
    return {x: x, y: y};
}

var fsCore = new FormSave();