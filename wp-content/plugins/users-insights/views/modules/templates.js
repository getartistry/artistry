angular.module('usinModuleApp').run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('views/modules/license.html',
    "<div ng-if=\"!module.uses_module_license\">\n" +
    "	<label ng-if=\"!licenseSet()\">{{strings.enterLicense}}</label>\n" +
    "	<label ng-if=\"licenseSet()\">{{strings.licenseKey}}</label>\n" +
    "	\n" +
    "	<input type=\"text\" ng-model=\"module.options.license.key\" />\n" +
    "	<a class=\"usin-btn usin-add-licence-btn\" ng-click=\"addLicense()\" ng-if=\"!licenseSet()\">{{strings.addLicense}}</a>\n" +
    "	<a class=\"usin-btn usin-deactivate-licence-btn\" ng-click=\"deactivateLicense()\" ng-if=\"licenseSet()\">{{strings.removeLicense}}</a>\n" +
    "	<div class=\"clear\"></div>\n" +
    "\n" +
    "\n" +
    "	<span class=\"usin-text-error\" ng-show=\"errorMsg\"><span class=\"usin-icon-close\"></span>{{strings.error}}: {{errorMsg}}</span>\n" +
    "	<span class=\"usin-text-success\" ng-show=\"successMsg\"> <span class=\"usin-icon-apply\"></span>{{successMsg}}</span>\n" +
    "\n" +
    "	<div ng-if=\"module.options.license.status_text && licenseSet()\">\n" +
    "		<span ng-class=\"['usin-license-status', 'usin-license-'+module.options.license.status]\">{{module.options.license.status_text}}</span>\n" +
    "		| <a class=\"usin-refresh-license\" ng-click=\"refreshLicense()\">{{strings.refresh}}</a>\n" +
    "	</div>\n" +
    "	\n" +
    "	<span class=\"usin-icon-license-loading\" ng-show=\"licenseLoading\"></span>\n" +
    "\n" +
    "</div>\n" +
    "\n" +
    "<div ng-if=\"module.uses_module_license\">\n" +
    "	<p>{{strings.noModuleLicense.replace('%s', getModule(module.uses_module_license).name)}}</p>\n" +
    "</div>"
  );


  $templateCache.put('views/modules/main.html',
    "<div>\n" +
    "	<div class=\"usin-modules-title-wrap\">\n" +
    "	<h2 class=\"usin-modules-title\">{{strings.activeModules}}</h2>\n" +
    "	</div>\n" +
    "	<div class=\"usin-columns\">\n" +
    "		<div ng-repeat=\"module in activeModules = (modules | moduleActive:true)\" class=\"usin-module-wrap usin-one-third usin-column\">\n" +
    "			<div class=\"usin-module usin-module-{{module.id}}\"></div>\n" +
    "		</div>\n" +
    "	</div>\n" +
    "	<div ng-if=\"!activeModules.length\"><p>{{strings.noActiveModules}}</p></div>\n" +
    "	<div class=\"usin-modules-title-wrap\">\n" +
    "	<h2 class=\"usin-modules-title\">{{strings.inactiveModules}}</h2>\n" +
    "	</div>\n" +
    "	<div class=\"usin-columns\">\n" +
    "		<div ng-repeat=\"module in inactiveModules = (modules | moduleActive:false)\" class=\"usin-module-wrap usin-one-third usin-column\">\n" +
    "			<div class=\"usin-module usin-module-{{module.id}}\"></div>\n" +
    "		</div>\n" +
    "	</div>\n" +
    "	<div ng-if=\"!inactiveModules.length\"><p>{{strings.noInactiveModules}}</p></div>\n" +
    "\n" +
    "</div>"
  );


  $templateCache.put('views/modules/module.html',
    "<div ng-class=\"{'usin-module-active': module.active, 'usin-module-inactive': !module.active, 'usin-module-edit': module.status == 'edit'}\">\n" +
    "	<div class=\"usin-module-head\">\n" +
    "		<div class=\"usin-module-icon\">\n" +
    "			<span class=\"usin-icon-{{module.id}}\"></span>\n" +
    "		</div>\n" +
    "		<h3 class=\"usin-module-title\">{{module.name}}</h3>\n" +
    "		<span class=\"usin-icon-close\" ng-click=\"setStatusDefault()\"></span>\n" +
    "		<span class=\"usin-module-beta-tag\" ng-if=\"module.in_beta\" ng-hide=\"module.status == 'edit'\">{{strings.beta}}</span>\n" +
    "	</div>\n" +
    "\n" +
    "	<div class=\"usin-module-content\">\n" +
    "		<h3 class=\"usin-module-title\">{{module.name}}</h3>\n" +
    "\n" +
    "		<div ng-switch=\"module.status\">\n" +
    "\n" +
    "			<div ng-switch-when=\"edit\">\n" +
    "				<div class=\"usin-license\" ng-if=\"module.requires_license\"></div>	\n" +
    "\n" +
    "			</div>\n" +
    "			\n" +
    "			<p ng-switch-default>{{module.desc}}</p>\n" +
    "\n" +
    "		</div>\n" +
    "		\n" +
    "	</div>\n" +
    "\n" +
    "	<div class=\"usin-module-footer\">\n" +
    "		<a class=\"usin-btn usin-btn-main\" ng-if=\"!module.active\" ng-click=\"onActivateClick()\"\n" +
    "			ng-class=\"{'usin-btn-disabled' : module.status == 'edit' && !module.options.license.activated}\">\n" +
    "			{{strings.activateModule}}\n" +
    "		</a>\n" +
    "\n" +
    "		<a class=\"usin-btn usin-btn-main\" ng-if=\"module.active && module.has_options\" ng-click=\"setStatusEdit()\"\n" +
    "			ng-class=\"{'usin-btn-disabled' : module.status == 'edit'}\">\n" +
    "			{{strings.settings}}\n" +
    "		</a>\n" +
    "\n" +
    "		<a class=\"usin-btn\" ng-if=\"module.active && module.allow_deactivate\" ng-click=\"onDeactivateClick()\">\n" +
    "			{{strings.deactivateModule}}\n" +
    "		</a>\n" +
    "\n" +
    "\n" +
    "		<a ng-repeat=\"button in module.buttons\" href=\"{{button.link}}\" class=\"usin-btn\" target=\"{{button.target || '_self'}}\">\n" +
    "			{{button.text}}\n" +
    "		</a>\n" +
    "\n" +
    "		<span class=\"usin-icon-module-loading\" ng-show=\"moduleLoading\"></span>\n" +
    "		<div class=\"usin-text-error\" ng-if=\"moduleError\">{{moduleError}}</div>\n" +
    "	</div>\n" +
    "</div>"
  );

}]);
