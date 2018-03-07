angular.module('usinApp').run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('views/user-list/bulk-actions.html',
    "<div class=\"usin-bulk-actions-wrap\" ng-show=\"bulkActions.getCount()>0\">\n" +
    "	<div class=\"usin-checked-users usin-float-left\">\n" +
    "		<p>{{ bulkActions.getCount() === 1 ? strings.userSelected : strings.usersSelected.replace('%d', bulkActions.getCount())}}</p>\n" +
    "		\n" +
    "	</div>\n" +
    "	\n" +
    "	<div class=\"usin-btn\" ng-click=\"toggleOptions()\" ng-class=\"{'usin-btn-drop-down-opened' : optionsVisible === true}\">\n" +
    "		{{strings.bulkActions}}\n" +
    "		<span class=\"usin-icon-drop-down usin-btn-drop-down\"></span>\n" +
    "	</div>\n" +
    "	<div class=\"usin-drop-down usin-bulk-actions-options usin-animate\" ng-show=\"optionsVisible\" click-outside=\"optionsVisible=false\">\n" +
    "		<ul>\n" +
    "		    <li ng-click=\"showGroupEditDialog('add')\">{{strings.addGroup}}</li>\n" +
    "		    <li ng-click=\"showGroupEditDialog('remove')\">{{strings.removeGroup}}</li>\n" +
    "		</ul>\n" +
    "  	</div>\n" +
    "	\n" +
    "   \n" +
    "</div>"
  );


  $templateCache.put('views/user-list/confirm-dialog.html',
    "<md-dialog aria-label=\"{{title}}\">\n" +
    "	<md-toolbar>\n" +
    "		<div class=\"md-toolbar-tools\">\n" +
    "			<h2>{{title}}</h2>\n" +
    "			<span flex></span>\n" +
    "			<md-button class=\"md-icon-button\" ng-click=\"close()\">\n" +
    "				<md-icon class=\"usin-icon-delete\" aria-label=\"Close dialog\"></md-icon>\n" +
    "			</md-button>\n" +
    "		</div>\n" +
    "	</md-toolbar>\n" +
    "\n" +
    "	<md-dialog-content>\n" +
    "		<div class=\"md-dialog-content\">\n" +
    "			<div ng-if=\"content\" ng-bind-html=\"content\"></div>\n" +
    "			<div ng-if=\"message\">\n" +
    "				<p>{{message}}</p>\n" +
    "			</div>\n" +
    "			<div class=\"usin-error\" ng-if=\"error\">{{error}}</div>\n" +
    "		</div>\n" +
    "	</md-dialog-content>\n" +
    "\n" +
    "	<md-dialog-actions layout=\"row\">\n" +
    "		<div class=\"usin-icon-simple-loading\" ng-show=\"isLoading\"></div>\n" +
    "		<button class=\"usin-btn\" ng-click=\"close()\">\n" +
    "			{{strings.cancel}}\n" +
    "		</button>\n" +
    "		<button class=\"usin-btn usin-btn-main\" ng-click=\"callAction()\">\n" +
    "			{{actionText}}\n" +
    "		</button>\n" +
    "	</md-dialog-actions>\n" +
    "</md-dialog>"
  );


  $templateCache.put('views/user-list/create-segment-dialog.html',
    "<md-dialog aria-label=\"{{strings.newSegment}}\">\n" +
    "	<form ng-cloak>\n" +
    "		<md-toolbar>\n" +
    "			<div class=\"md-toolbar-tools\">\n" +
    "				<h2>{{strings.newSegment}}</h2>\n" +
    "				<span flex></span>\n" +
    "				<md-button class=\"md-icon-button\" ng-click=\"closeDialog()\">\n" +
    "					<md-icon class=\"usin-icon-delete\" aria-label=\"Close dialog\"></md-icon>\n" +
    "				</md-button>\n" +
    "			</div>\n" +
    "		</md-toolbar>\n" +
    "\n" +
    "		<md-dialog-content>\n" +
    "			<div class=\"md-dialog-content\">\n" +
    "				<label>{{strings.segmentName}}</label>\n" +
    "				<input ng-model=\"segmentName\" class=\"usin-input\" type=\"text\" ng-keypress=\"$event.keyCode==13 && doOnEnter($event)\">\n" +
    "				<div class=\"usin-error\" ng-if=\"error\">{{error}}</div>\n" +
    "			</div>\n" +
    "		</md-dialog-content>\n" +
    "\n" +
    "		<md-dialog-actions layout=\"row\">\n" +
    "			<div class=\"usin-icon-simple-loading\" ng-show=\"isLoading\"></div>\n" +
    "			<button class=\"usin-btn\" ng-click=\"closeDialog()\">\n" +
    "				{{strings.cancel}}\n" +
    "			</button>\n" +
    "			<button class=\"usin-btn usin-btn-main\" ng-click=\"saveSegment()\" ng-disabled=\"!segmentName\">\n" +
    "				{{strings.saveSegment}}\n" +
    "			</button>\n" +
    "		</md-dialog-actions>\n" +
    "	</form>\n" +
    "</md-dialog>"
  );


  $templateCache.put('views/user-list/date-pick.html',
    "<span>\n" +
    "	<span ng-show=\"isDateOperator(operator)\">\n" +
    "		<md-datepicker ng-model=\"date\" ng-change=\"updateDate()\" md-open-on-focus></md-datepicker>\n" +
    "	</span>\n" +
    "\n" +
    "	<span ng-show=\"!isDateOperator(operator)\" class=\"usin-days-ago-filter\">\n" +
    "		<input type=\"number\" min=\"0\" ng-model=\"daysAgo\" ng-change=\"updateDaysAgo()\" class=\"usin-number-field\" />\n" +
    "		<span class=\"usin-filter-suffix\">{{strings.daysAgo}}</span>\n" +
    "	</span>\n" +
    "</span>"
  );


  $templateCache.put('views/user-list/filter.html',
    "<div class=\"usin-filter-wrap\">\n" +
    "	<button class=\"usin-btn usin-btn-main usin-btn-filter\" ng-click=\"addFilter()\" ng-class=\"{'usin-btn-disabled': loading.isLoading()}\">\n" +
    "		<span class=\"usin-icon-filter usin-icon-left\" /> {{strings.addFilter}}\n" +
    "	</button>\n" +
    "	\n" +
    "	<div class=\"usin-filter-set\" ng-repeat=\"(key, filter) in filters\">\n" +
    "		\n" +
    "		<div ng-if=\"!filter.applied\" class=\"usin-filter-form\">\n" +
    "\n" +
    "			<usin-search-select ng-model=\"filter.by\" usin-options=\"fields\" usin-option-key=\"id\" usin-option-val=\"name\" ng-change=\"doOnFieldSelected(filter)\" class=\"usin-field-select\"></usin-search-select>\n" +
    "\n" +
    "			<span ng-show=\"filter.type\" ng-keyup=\"$event.keyCode==13 && applyFilter(filter)\">\n" +
    "				<usin-search-select ng-model=\"filter.operator\" usin-options=\"fieldOperators\" ng-hide=\"fieldOperators.length<=1\" class=\"usin-operator-select\"></usin-search-select>\n" +
    "\n" +
    "				<span ng-if=\"isOptionField(filter.type)\">\n" +
    "					<!-- filter a select field -->\n" +
    "					<usin-search-select ng-model=\"filter.condition\" usin-options=\"filter.options\" ng-hide=\"isNullOperator(filter.operator)\" class=\"usin-condition-select\"></usin-search-select>\n" +
    "				</span>\n" +
    "				\n" +
    "				<span ng-if=\"filter.type=='date'\">\n" +
    "					<!-- filter by date -->\n" +
    "					<span usin-date-filter condition=\"filter.condition\" operator=\"filter.operator\" by=\"filter.by\"\n" +
    "						ng-hide=\"isNullOperator(filter.operator)\"></span>\n" +
    "				</span>\n" +
    "\n" +
    "				<span ng-if=\"filter.type=='number'\">\n" +
    "					<!-- filter by number -->\n" +
    "					<input type=\"number\" ng-model=\"filter.condition\" ng-hide=\"isNullOperator(filter.operator)\">\n" +
    "				</span>\n" +
    "\n" +
    "				<span ng-if=\"isTextField(filter.type)\">\n" +
    "					<!-- filter by text -->\n" +
    "					<input type=\"text\" ng-model=\"filter.condition\" ng-hide=\"isNullOperator(filter.operator)\">\n" +
    "				</span>\n" +
    "				\n" +
    "				<button class=\"usin-btn usin-btn-main usin-btn-apply usin-icon-apply\" ng-click=\"applyFilter(filter)\" ></button>\n" +
    "			</span>\n" +
    "			<span class=\"usin-btn-close usin-icon-close\" ng-click=\"remove(filter)\" />	\n" +
    "		</div>\n" +
    "		\n" +
    "		<div ng-if=\"filter.applied\" class=\"usin-filter-preview usin-btn\" ng-class=\"{'usin-disabled': filter.disabled}\">\n" +
    "			<md-tooltip md-direction=\"top\" ng-if=\"filter.disabled\">{{strings.fieldNotExist}}</md-tooltip>\n" +
    "			<span class=\"usin-filter-preview-text\" ng-click=\"edit(filter)\">\n" +
    "				{{filter.label}} <span class=\"usin-filter-operator\">{{filter.operator | previewOperator:filter.type}}</span> {{filter | previewCondition}}\n" +
    "			</span>\n" +
    "			<span class=\"usin-btn-close usin-icon-close\" ng-click=\"remove(filter)\" ng-class=\"{'usin-btn-disabled': loading.isLoading()}\" />\n" +
    "		</div>\n" +
    "	</div>\n" +
    "\n" +
    "</div>"
  );


  $templateCache.put('views/user-list/group-edit-dialog.html',
    "<md-dialog aria-label=\"{{title}}\">\n" +
    "	<form ng-cloak>\n" +
    "		<md-toolbar>\n" +
    "			<div class=\"md-toolbar-tools\">\n" +
    "				<h2>{{title}}</h2>\n" +
    "				<span flex></span>\n" +
    "				<md-button class=\"md-icon-button\" ng-click=\"cancel()\">\n" +
    "					<md-icon class=\"usin-icon-delete\" aria-label=\"Close dialog\"></md-icon>\n" +
    "				</md-button>\n" +
    "			</div>\n" +
    "		</md-toolbar>\n" +
    "\n" +
    "		<md-dialog-content class=\"usin-group-edit-wrap\">\n" +
    "			<div class=\"md-dialog-content\">\n" +
    "				<div ng-show=\"groups.length\">\n" +
    "					<p>{{info}}</p>\n" +
    "\n" +
    "					<md-input-container>\n" +
    "						<md-select ng-model=\"selectedGroup\" placeholder=\"{{strings.selectGroup}}\">\n" +
    "							<md-option ng-value=\"group.key\" ng-repeat=\"group in groups\" md-no-ink=\"true\">{{ group.val }}</md-option>\n" +
    "						</md-select>\n" +
    "					</md-input-container>\n" +
    "\n" +
    "					<div class=\"usin-error\" ng-if=\"error\">{{error}}</div>\n" +
    "				</div>\n" +
    "				<div ng-show=\"!groups.length\">\n" +
    "					<p>{{strings.noGroups}}</p>\n" +
    "				</div>\n" +
    "			</div>\n" +
    "		</md-dialog-content>\n" +
    "\n" +
    "		<md-dialog-actions layout=\"row\">\n" +
    "			<div class=\"usin-icon-simple-loading\" ng-show=\"isLoading\"></div>\n" +
    "			<span flex></span>\n" +
    "			<button class=\"usin-btn\" ng-click=\"cancel()\">\n" +
    "				{{strings.cancel}}\n" +
    "			</button>\n" +
    "			<button class=\"usin-btn usin-btn-main\" ng-click=\"apply()\" ng-disabled=\"!selectedGroup\" ng-show=\"groups.length\">\n" +
    "				{{strings.apply}}\n" +
    "			</button>\n" +
    "\n" +
    "		</md-dialog-actions>\n" +
    "	</form>\n" +
    "</md-dialog>"
  );


  $templateCache.put('views/user-list/list-options.html',
    "<div class=\"usin-options-wrap\">\n" +
    "	<div class=\"usin-bulk-actions usin-float-left\" ng-if=\"listView && canUpdateUsers\"></div>\n" +
    "	<div class=\"usin-segments\" ng-hide=\"!listView || !total.current\"></div>\n" +
    "	\n" +
    "	<button class=\"usin-btn usin-btn-export\" ng-if=\"canExportUsers\" ng-hide=\"!listView || !total.current\" \n" +
    "		ng-click=\"showConfirm()\" ng-disabled=\"bulkActions.isAnyChecked()\"> \n" +
    "		<span class=\"usin-icon-export\" />\n" +
    "		<md-tooltip md-direction=\"top\">{{strings.export.replace('%d', total.current)}}</md-tooltip>\n" +
    "	</button>\n" +
    "\n" +
    "	<button class=\"usin-btn usin-btn-list-options\" ng-click=\"toggleDisplayed()\" ng-hide=\"!listView || !total.current\" ng-disabled=\"bulkActions.isAnyChecked()\"> \n" +
    "		<span class=\"usin-icon-visible usin-btn-drop-down\" ng-class=\"{'usin-btn-drop-down-opened' : displayed === true}\"/>\n" +
    "		<md-tooltip md-direction=\"top\">{{strings.toggleColumns}}</md-tooltip>\n" +
    "	</button>\n" +
    "		\n" +
    "	<button class=\"usin-btn usin-btn-map\" ng-click=\"onToggleView()\" ng-disabled=\"bulkActions.isAnyChecked()\"\n" +
    "		ng-class=\"{'usin-btn-map-active' : !listView}\" ng-if=\"showMap\" ng-hide=\"listView && !total.current\"> \n" +
    "		<span class=\"usin-icon-map\"/>\n" +
    "		<md-tooltip md-direction=\"top\" md-autohide>{{listView ? strings.enterMapView : strings.exitMapView}}</md-tooltip>\n" +
    "	</button>\n" +
    "	<div class=\"usin-fields-settings usin-drop-down usin-animate ng-hide\" ng-show=\"displayed\" click-outside=\"displayed=false\">\n" +
    "		<ul dnd-list=\"fields\">\n" +
    "			<li ng-repeat=\"field in fields\" dnd-draggable=\"field\" dnd-moved=\"reorder($index)\" dnd-disable-if=\"field.disableHide\">\n" +
    "				<dnd-nodrag>\n" +
    "					<span>\n" +
    "						<md-checkbox ng-checked=\"field.show\" ng-click=\"onCheckboxChange(field)\" md-no-ink=\"true\"\n" +
    "							aria-label=\"Toggle Column {{field.name}}\" ng-disabled=\"loading.isLoading() || field.disableHide\"></md-checkbox>\n" +
    "						<span class=\"usin-icon-{{field.icon}}\"></span>\n" +
    "						{{field.name}}\n" +
    "						<div dnd-handle class=\"usin-drag-handle\" ng-if=\"!field.disableHide\">:::</div>\n" +
    "						<div class=\"usin-drag-handle usin-disabled\" ng-if=\"field.disableHide\">:::</div>\n" +
    "					</span>\n" +
    "				</dnd-nodrag>\n" +
    "			</li>\n" +
    "			<li class=\"dndPlaceholder\"><label></label></li>\n" +
    "\n" +
    "		</ul>\n" +
    "	</div>\n" +
    "</div>\n" +
    "\n" +
    "\n"
  );


  $templateCache.put('views/user-list/list.html',
    "<div>\n" +
    "	<div class=\"usin-table-wrap\">\n" +
    "	<table class=\"usin-table usin-user-table\" ng-show=\"userList.users.length\" ng-class=\"{'usin-bulk-actions-checked': bulkActions.isAnyChecked()}\">\n" +
    "	<thead>\n" +
    "		<tr>\n" +
    "			<th ng-repeat=\"field in showFields\" ng-class=\"{'usin-sortable' : field.order !== false}\">\n" +
    "				<span ng-if=\"field.id=='username'\" class=\"usin-heading-checkbox\">\n" +
    "					<md-checkbox aria-label=\"Select All\"\n" +
    "								ng-checked=\"bulkActions.isAllChecked()\"\n" +
    "								md-indeterminate=\"bulkActions.isAllIndeterminate()\"\n" +
    "								ng-click=\"bulkActions.toggleAll()\"\n" +
    "								class=\"usin-toggler-checkbox\">\n" +
    "					</md-checkbox>\n" +
    "					<md-tooltip md-direction=\"top\">{{bulkActions.isAllChecked() ? strings.clearSelection: strings.selectAllUsers}}</md-tooltip>\n" +
    "				</span>\n" +
    "				<span ng-click=\"setOrderBy(field.id)\">\n" +
    "					<span class=\"usin-heading-{{field.id}}\">{{field.name}}</span>\n" +
    "					<span class=\"usin-order-arrow\" ng-class=\"{'usin-order-arrow-up' : userList.order == 'ASC', 'usin-order-arrow-down': userList.order == 'DESC'}\"\n" +
    "						ng-show=\"userList.orderBy==field.id\"></span>\n" +
    "				</span>\n" +
    "			</th>\n" +
    "		</tr>\n" +
    "	</thead>\n" +
    "	<tr ng-repeat=\"user in userList.users\">\n" +
    "		<td ng-repeat=\"field in showFields\" ng-switch=\"field.id\" title=\"{{field.name}} ({{user.username}})\" class=\"usin-field-{{field.id}}\">\n" +
    "			<span ng-switch-when=\"username\" class=\"usin-username-clickable usin-username-wrap\">\n" +
    "				<span class=\"usin-online-circle\" ng-if=\"user.online\" title=\"{{strings.online}}\"></span>\n" +
    "				<span class=\"user-avatar-actions\">\n" +
    "					<span ng-bind-html=\"user.avatar\" class=\"usin-avatar-wrap\"></span>\n" +
    "					<md-checkbox ng-checked=\"bulkActions.isChecked(user.ID)\" ng-click=\"bulkActions.toggle(user.ID)\" \n" +
    "						aria-label=\"Select User\" md-no-ink=\"true\"></md-checkbox>\n" +
    "				</span>\n" +
    "				<span class=\"usin-username\" ng-click=\"openProfile(user)\">{{user.username}}</span>\n" +
    "			</span>\n" +
    "			\n" +
    "			<span ng-switch-when=\"user_groups\">\n" +
    "				<span ng-repeat=\"groupId in user.user_groups\" ng-bind-html=\"groupId | groupTagHtml\"></span>\n" +
    "			</span>\n" +
    "\n" +
    "			<span ng-switch-default>\n" +
    "				<span ng-if=\"field.allowHtml\" ng-bind-html=\"user[field.id]\" class=\"usin-field-value\"></span>\n" +
    "				<span ng-if=\"!field.allowHtml\" class=\"usin-field-value\">{{user[field.id]}}</span>\n" +
    "			</span>\n" +
    "		</td>\n" +
    "	</tr>\n" +
    "	<tfoot>\n" +
    "		<tr>\n" +
    "			<th ng-repeat=\"field in showFields\" ng-class=\"{'usin-sortable' : field.order !== false}\">\n" +
    "				<span ng-click=\"setOrderBy(field.id)\">\n" +
    "					<span class=\"usin-heading-{{field.id}}\">{{field.name}}</span>\n" +
    "					<span class=\"usin-order-arrow\" ng-class=\"{'usin-order-arrow-up' : userList.order == 'ASC', 'usin-order-arrow-down': userList.order == 'DESC'}\"\n" +
    "						ng-show=\"userList.orderBy==field.id\"></span>\n" +
    "				</span>\n" +
    "			</th>\n" +
    "		</tr>\n" +
    "	</tfoot>\n" +
    "	</table>\n" +
    "	</div>\n" +
    "\n" +
    "	<div class=\"usin-no-results\" ng-show=\"!loading.isLoading() && !userList.users.length\">\n" +
    "		<div class=\"usin-no-results-logo\"></div>\n" +
    "		<h3> {{strings.noResults}}</h3>\n" +
    "	</div>\n" +
    "\n" +
    "	<div class=\"usin-pagination-wrapper\" ng-controller=\"UsinPaginationCtrl\" ng-show=\"pages > 1\">\n" +
    "		<div class=\"usin-pagination\">\n" +
    "			<button class=\"usin-btn usin-pag-btn\" ng-disabled=\"userList.page==1\" ng-click=\"changePage(userList.page-1)\"><span class=\"usin-icon-arrow-left\"></span></button>\n" +
    "			<button class=\"usin-btn usin-pag-btn\" ng-disabled=\"userList.page==pages\" ng-click=\"changePage(userList.page+1)\"><span class=\"usin-icon-arrow-right\"></span></button>\n" +
    "			<span class=\"usin-gotopage\">\n" +
    "			<input type=\"text\" ng-model=\"userPage\" ng-keyup=\"$event.keyCode==13 && changePage(userPage)\">\n" +
    "			{{strings.of}} {{pages}}\n" +
    "		</span>\n" +
    "		</div>\n" +
    "		<div class=\"usin-pagination-circular-loading\" ng-class=\"{'usin-in-loading': loading.isLoading()}\"></div>\n" +
    "		<div class=\"usin-pagination-options\">\n" +
    "			<div>\n" +
    "				<span>{{strings.usersPerPage}}</span>\n" +
    "				<select ng-model=\"$parent.userList.usersPerPage\" ng-change=\"onUsersPerPageChange()\" ng-options=\"o as o for o in pageOptions\"></select>\n" +
    "			</div>\n" +
    "		</div>\n" +
    "	</div>\n" +
    "\n" +
    "	<div ng-class=\"{'usin-in-loading': loading.isLoading() && !userList.users.length}\">\n" +
    "		<div class=\"usin-loading\"> <span class=\"usin-loading-dot\"></span><span class=\"usin-loading-dot usna-dot2\"></span></div>\n" +
    "	</div>\n" +
    "</div>"
  );


  $templateCache.put('views/user-list/main.html',
    "<div>\n" +
    "	<div usin-filter fields=\"filterFields\" filters=\"filters\" loading=\"loading\" broadcast-change=\"applyFilters()\"></div>\n" +
    "</div>\n" +
    "\n" +
    "\n" +
    "<div class=\"usin-float-right usin-options-menu\">\n" +
    "	<div class=\"usin-circular-loading\" ng-class=\"{'usin-in-loading': loading.isLoading() && (total.current || !listView)}\"></div>\n" +
    "	<div class=\"usin-list-options\"></div>\n" +
    "</div>\n" +
    "\n" +
    "<div class=\"usin-total\">\n" +
    "	<span class=\"usin-list-total\" ng-show=\"total.current && listView\">\n" +
    "		<span class=\"usin-icon-people\"></span>\n" +
    "		<span class=\"usin-total-current-number\" ng-show=\"total.current !== total.all\">\n" +
    "			{{ total.current }} / \n" +
    "		</span>\n" +
    "		<span class=\"usin-total-number\" >\n" +
    "			{{ total.all }} \n" +
    "		</span> <span>{{strings.users}}</span>\n" +
    "	</span>\n" +
    "	<span class=\"usin-map-total\" ng-show=\"!listView && total.map!==null\">\n" +
    "		<span class=\"usin-icon-map\"></span>\n" +
    "		<span class=\"usin-map-total-number\">{{total.map}}</span>\n" +
    "		<span>{{ strings.mapUsersDetected }}</span>\n" +
    "	</span>\n" +
    "</div>\n" +
    "<div class=\"clear\"></div>\n" +
    "\n" +
    "<div class=\"usin-error\" ng-show=\"errorMsg\">\n" +
    "	{{strings.error}}: {{errorMsg}}\n" +
    "</div>\n" +
    "\n" +
    "<div class=\"usin-map-view\" ng-if=\"!listView\" ng-controller=\"UsinMapCtrl\">\n" +
    "	<div usin-map id=\"usin-list-map\" map-options=\"mapOptions\"></div>\n" +
    "</div>\n" +
    "\n" +
    "<div class=\"usin-list-view\" ng-if=\"listView\"></div>\n" +
    "\n" +
    "\n"
  );


  $templateCache.put('views/user-list/profile-editable-field.html',
    "<div ng-class=\"['usin-editable-field', {'usin-field-editing': editing}]\">\n" +
    "	<span class=\"field-name\">{{field.name}}: </span>\n" +
    "	<span class=\"field-value\" ng-hide=\"editing\">{{user[field.id] || '-'}}</span>\n" +
    "	\n" +
    "	<span ng-if=\"canUpdateUsers\">\n" +
    "		<input type=\"text\" ng-if=\"field.filter.type=='text' || field.filter.type=='date'\" ng-model=\"user[field.id]\" ng-show=\"editing\" ng-keyup=\"$event.keyCode==13 && updateField()\">\n" +
    "		<input type=\"number\" usin-string-to-number ng-if=\"field.filter.type=='number'\" ng-model=\"user[field.id]\" ng-show=\"editing\" ng-keyup=\"$event.keyCode==13 && updateField()\">\n" +
    "		\n" +
    "		<div class=\"usin-btn-edit usin-icon-edit alignright\" ng-click=\"toggleEdit()\" ng-show=\"!editing\"></div>\n" +
    "		<div class=\"usin-btn-apply usin-icon-apply alignright\" ng-click=\"updateField()\" ng-show=\"editing && !loading\"></div>\n" +
    "		<div class=\"usin-icon-simple-loading usin-group-loading alignright\" ng-show=\"loading\"></div>\n" +
    "		<div class=\"usin-error\" ng-show=\"errorMsg\">{{errorMsg}}</div>\n" +
    "	</span>\n" +
    "	<div class=\"clear\"></div>\n" +
    "</div>"
  );


  $templateCache.put('views/user-list/profile-groups.html',
    "<div class=\"usin-profile-groups-wrapper\">\n" +
    "	<div>\n" +
    "		<span class=\"field-name\">{{strings.groups}}:</span> {{userGroupNames()}}\n" +
    "		<span ng-show=\"!user.user_groups.length\">-</span>\n" +
    "		<span ng-repeat=\"groupId in user.user_groups\" ng-bind-html=\"groupId | groupTagHtml\"></span>\n" +
    "		<span ng-if=\"canUpdateUsers\">\n" +
    "			<div class=\"usin-btn-edit usin-icon-edit alignright\" ng-click=\"toggleEdit()\" ng-show=\"!editing && allGroups.length\"></div>\n" +
    "			<div class=\"usin-btn-apply usin-icon-apply alignright\" ng-click=\"updateGroups()\" ng-show=\"!groupLoading && editing\"></div>\n" +
    "			<div class=\"usin-icon-simple-loading usin-group-loading alignright\" ng-show=\"groupLoading\"></div>\n" +
    "			<div class=\"usin-groups-list\" ng-show=\"editing\">\n" +
    "				<div class=\"usin-error\" ng-show=\"groupErrorMsg\">{{groupErrorMsg}}</div>\n" +
    "				<ul>\n" +
    "					<li ng-repeat=\"group in allGroups\">\n" +
    "						<md-checkbox ng-checked=\"userHasGroup(group.key)\" md-no-ink=\"true\"\n" +
    "							aria-label=\"Toggle Group {{group.val}}\" ng-click=\"toggleGroup(group.key)\"></md-checkbox>\n" +
    "						<span>{{group.val}}</span>\n" +
    "					</li>\n" +
    "				</ul>\n" +
    "			</div>\n" +
    "		</span>\n" +
    "	</div>\n" +
    "</div>"
  );


  $templateCache.put('views/user-list/profile-notes.html',
    "<div class=\"usin-notes\">\n" +
    "	<h3 class=\"usin-profile-title\">{{strings.notes}}</h3>\n" +
    "	\n" +
    "	<div class=\"usin-notes-form\" ng-show=\"canUpdateUsers\">\n" +
    "		<textarea ng-model=\"noteContent\" ng-keyup=\"$event.keyCode==13 && addNote()\" class=\"usin-note-field\" rows=\"3\"></textarea>\n" +
    "		<span class=\"usin-btn usin-btn-main usin-btn-note\" ng-click=\"addNote()\">{{strings.addNote}}</span>\n" +
    "		<div class=\"usin-icon-simple-loading usin-note-loading alignright\" ng-show=\"noteLoading\"></div>\n" +
    "	</div>\n" +
    "	\n" +
    "	<div class=\"clear\"></div>\n" +
    "	<div class=\"usin-error\" ng-show=\"noteErrorMsg\">{{noteErrorMsg}}</div>\n" +
    "	\n" +
    "	<div ng-if=\"user.notes\" class=\"usin-notes-list\">\n" +
    "		<div ng-repeat=\"(index, note) in user.notes\" ng-class=\"['usin-note', 'usin-note-'+note.state]\">\n" +
    "			<div class=\"usin-note-content\">{{note.content}}</div>\n" +
    "			<div class=\"usin-note-info\">{{strings.by}} {{note.by}} | {{note.date}}\n" +
    "			<span class=\"alignright usin-note-delete\" ng-if=\"canUpdateUsers\" usin-confirmed-click=\"deleteNote(note.id, index)\" usin-confirm-click=\"{{strings.areYouSure}}\">{{strings.delete}}</span>\n" +
    "			<span class=\"usin-custom-directive\" ng-repeat=\"ct in customTemplates['note_actions']\" ct=\"ct\" ></span>\n" +
    "			</div>\n" +
    "			\n" +
    "		</div>\n" +
    "	</div>\n" +
    "	\n" +
    "	<div class=\"usin-custom-directive\" ng-repeat=\"ct in customTemplates['after_notes']\" ct=\"ct\" ></div>\n" +
    "</div>"
  );


  $templateCache.put('views/user-list/profile.html',
    "<div class=\"usin-profile\">\n" +
    "\n" +
    "	<div class=\"usin-profile-buttons\">\n" +
    "		<a class=\"usin-btn usin-profile-back-btn\" href=\"#/\"><span class=\"usin-icon-arrow-left\"></span> {{strings.back}}</a>\n" +
    "\n" +
    "		<div class=\"usin-profile-actions\" ng-if=\"user.actions.length\">\n" +
    "			<a ng-repeat=\"action in user.actions\" href=\"{{action.link}}\" target=\"_blank\" class=\"usin-btn\">\n" +
    "				<span class=\"usin-icon-{{action.id}}\"></span>\n" +
    "				<md-tooltip md-direction=\"top\" ng-if=\"action.name\">{{action.name}}</md-tooltip>\n" +
    "			</a>\n" +
    "		</div>\n" +
    "	</div>\n" +
    "\n" +
    "	<div ng-show=\"loading\" class=\"usin-in-loading\">\n" +
    "		<div class=\"usin-loading\"> <span class=\"usin-loading-dot\"></span><span class=\"usin-loading-dot usna-dot2\"></span></div>\n" +
    "	</div>\n" +
    "\n" +
    "	<div class=\"usin-error\" ng-show=\"errorMsg\">\n" +
    "	{{strings.error}}: {{errorMsg}}\n" +
    "	</div>\n" +
    "\n" +
    "<div ng-show=\"user && !loading\" class=\"usin-user-profile-container\" >\n" +
    "	<div ng-class=\"['usin-user-profile-wrap', 'usin-one-third', 'usin-column', {'usin-user-has-map':mapOptions}]\">\n" +
    "		<div class=\"usin-user-profile\">\n" +
    "			<div class=\"usin-profile-map-wrapper\" ng-if=\"mapOptions\">\n" +
    "		  		<div usin-map id=\"usin-profile-map\" map-options=\"mapOptions\"></div>\n" +
    "			</div>\n" +
    "		<div class=\"usin-avatar\" ng-bind-html=\"user.avatar\"></div>\n" +
    "\n" +
    "		<div class=\"usin-personal-data\">\n" +
    "			<div ng-repeat=\"field in fields | fieldsByType:'personal'\" class=\"usin-profile-field-{{field['id']}}\">\n" +
    "				<span class=\"field-name\">{{field.name}}:</span><span class=\"field-value\"> <h3>{{user[field['id']]}}</h3></span>\n" +
    "			</div> \n" +
    "		</div>\n" +
    "        <div class=\"clear\"></div>\n" +
    "		<div class=\"usin-general-data\">\n" +
    "				\n" +
    "			<!-- GROUPS -->\n" +
    "			<div usin-profile-groups></div>\n" +
    "			\n" +
    "			<div ng-repeat=\"field in fields | fieldsByType:'general'\" \n" +
    "				ng-class=\"usin-profile-field-{{field['id']}}\">\n" +
    "				\n" +
    "				<!-- NON-EDITABLE FIELDS: -->\n" +
    "				<div ng-if=\"!isFieldEditable(field['id']) && user[field['id']]\">\n" +
    "					<span class=\"field-name\">{{field.name}}: </span>\n" +
    "					<span class=\"field-value\">{{user[field.id]}}</span>\n" +
    "				</div>\n" +
    "				<!-- EDITABLE FIELDS: -->\n" +
    "				<div usin-profile-editable-field ng-if=\"isFieldEditable(field['id'])\"></div>\n" +
    "				\n" +
    "				\n" +
    "				<div ng-if=\"\"></div>\n" +
    "			</div> \n" +
    "		</div>\n" +
    "\n" +
    "	</div>\n" +
    "</div>\n" +
    "\n" +
    "<div class=\"usin-user-data-wrap usin-one-third usin-column\">\n" +
    "	<div class=\"usin-user-data\">\n" +
    "		<div class=\"usin-activity\">\n" +
    "			<h3 class=\"usin-profile-title\">{{strings.activity}}</h3>\n" +
    "			<ul ng-if=\"user.activity.length\">\n" +
    "				<li ng-repeat=\"item in user.activity\" class=\"usin-activity-item\">\n" +
    "					<h4 class=\"usin-act-title\">\n" +
    "						<span ng-class=\"['usin-act-icon', {'usin-icon-{{item.icon}}':item.icon, 'usin-icon-field': !item.icon}]\"></span>\n" +
    "						<span ng-if=\"!item.hide_count\">\n" +
    "							{{item.count}}\n" +
    "						</span>\n" +
    "						{{item.label}}\n" +
    "					</h4>\n" +
    "					<ul ng-if=\"item.list.length\" class=\"usin-activity-list\">\n" +
    "						<li ng-repeat=\"listItem in item.list\">\n" +
    "							<span class=\"usin-icon-list\"></span>\n" +
    "							<a ng-href=\"{{listItem.link}}\" target=\"_blank\" ng-bind-html=\"listItem.title\"></a>\n" +
    "							<div ng-if=\"listItem.details.length\" ng-repeat=\"details in listItem.details\" ng-bind-html=\"details\" class=\"usin-activity-details\"></div>\n" +
    "						</li>\n" +
    "						<li ng-if=\"item.list.length < item.count\" class=\"usin-list-more\">[...]</li>\n" +
    "					</ul>\n" +
    "					<a class=\"usin-act-more\" ng-href=\"{{item.link}}\" ng-if=\"item.link\" target=\"_blank\">{{strings.view}}</a>\n" +
    "				</li>\n" +
    "			</ul>\n" +
    "			<span ng-if=\"!user.activity.length\">\n" +
    "				{{strings.noActivity}}\n" +
    "			</span>\n" +
    "		</div>\n" +
    "	</div>\n" +
    "	</div>\n" +
    "	\n" +
    "	<div class=\"usin-user-notes-wrap usin-one-third usin-column\">\n" +
    "		<!-- NOTES -->\n" +
    "		<div usin-profile-notes></div>\n" +
    "	</div>\n" +
    "	\n" +
    "</div>\n" +
    "\n" +
    "</div>\n"
  );


  $templateCache.put('views/user-list/search-select.html',
    "<span>\n" +
    "	<ui-select search-enabled=\"{{usinOptions.length>20}}\" theme=\"select2\">\n" +
    "		<ui-select-match placeholder=\"{{usinPlaceholder}}\">{{$select.selected[usinOptionVal]}}</ui-select-match>\n" +
    "		<ui-select-choices repeat=\"field[usinOptionKey] as field in usinOptions | filter: $select.search\" position=\"down\">\n" +
    "			<span ng-if=\"field.icon\" class=\"usin-icon-{{field.icon}}\"></span>\n" +
    "		  	<span ng-bind-html=\"field[usinOptionVal] | highlight: $select.search\"></span>\n" +
    "		</ui-select-choices>\n" +
    "	</ui-select>\n" +
    "</span>"
  );


  $templateCache.put('views/user-list/segments.html',
    "<div class=\"usin-segments-wrap\">\n" +
    "	<button class=\"usin-btn\" ng-click=\"toggleOptions()\" ng-class=\"{'usin-btn-drop-down-opened' : optionsVisible === true}\"\n" +
    "		ng-disabled=\"bulkActions.isAnyChecked()\">\n" +
    "		<span class=\"usin-icon-segment\"/>\n" +
    "		<span class=\"usin-icon-drop-down usin-btn-drop-down\"></span>\n" +
    "		<md-tooltip md-direction=\"top\">{{strings.segments}}</md-tooltip>\n" +
    "	</button>\n" +
    "	\n" +
    "	<div class=\"usin-drop-down usin-segments-options usin-animate\" ng-show=\"optionsVisible\" click-outside=\"optionsVisible=false\">\n" +
    "		<ul class=\"usin-segments-list\">\n" +
    "			<li class=\"usin-create-segment-wrapper\" ng-if=\"canManageSegments\">\n" +
    "				<md-tooltip md-direction=\"top\" md-autohide>{{(filters | appliedFilters).length ? strings.saveSegmentTooltip : strings.disabledSegmentTooltip}}</md-tooltip>\n" +
    "				<button class=\"usin-save-segment usin-btn-small usin-btn-main\" ng-click=\"openSegmentDialog()\" ng-disabled=\"!(filters | appliedFilters).length\">\n" +
    "					<span class=\"usin-icon-add\"></span>\n" +
    "					{{strings.newSegment}}\n" +
    "				</button>\n" +
    "			</li>\n" +
    "			<li ng-repeat=\"segment in segments\">\n" +
    "				<span class=\"usin-icon-segment\"></span>\n" +
    "				<span class=\"usin-segment-name\" ng-click=\"applySegment(segment)\">{{segment.name}}</span>\n" +
    "				<span class=\"usin-icon-close usin-float-right\" ng-click=\"deleteSegment(segment)\" ng-if=\"canManageSegments\"></span>\n" +
    "			</li>\n" +
    "		</ul>\n" +
    "  	</div>\n" +
    "	\n" +
    "</div>\n"
  );

}]);
