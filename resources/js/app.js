require("./components/autoload");
require("../../vendor/marcusvbda/vstack/src/Assets/js/components/autoload");
require("./helpers");

VueApp.appendStoreModule("default", require("./stores/modules/default.module").default);
VueApp.start();
