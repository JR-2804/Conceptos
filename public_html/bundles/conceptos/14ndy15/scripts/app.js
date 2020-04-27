/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/scripts/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/scripts/app.js":
/*!*******************************!*\
  !*** ./assets/scripts/app.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _search_bar = __webpack_require__(/*! ./modules/app/_search_bar */ \"./assets/scripts/modules/app/_search_bar.js\");\n\nvar _search_bar2 = _interopRequireDefault(_search_bar);\n\nvar _image_viewer = __webpack_require__(/*! ./modules/app/_image_viewer */ \"./assets/scripts/modules/app/_image_viewer.js\");\n\nvar _image_viewer2 = _interopRequireDefault(_image_viewer);\n\nvar _contact_card = __webpack_require__(/*! ./modules/app/_contact_card */ \"./assets/scripts/modules/app/_contact_card.js\");\n\nvar _contact_card2 = _interopRequireDefault(_contact_card);\n\nfunction _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }\n\nnew _search_bar2.default();\nnew _image_viewer2.default();\nnew _contact_card2.default();\n\n//# sourceURL=webpack:///./assets/scripts/app.js?");

/***/ }),

/***/ "./assets/scripts/modules/app/_contact_card.js":
/*!*****************************************************!*\
  !*** ./assets/scripts/modules/app/_contact_card.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\n\nvar _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nvar _utils = __webpack_require__(/*! ../app/_utils */ \"./assets/scripts/modules/app/_utils.js\");\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nvar ContactCard = function () {\n  function ContactCard() {\n    _classCallCheck(this, ContactCard);\n\n    this.contactCard = document.querySelector(\".contact_card\");\n    this.contactCardClose = document.querySelector(\".contact_card__close\");\n\n    this.showOrNot();\n  }\n\n  _createClass(ContactCard, [{\n    key: \"showOrNot\",\n    value: function showOrNot() {\n      if ((0, _utils.getCookie)(\"contactCard\")) {\n        var cookieValue = (0, _utils.getCookieValue)(\"contactCard\");\n\n        if (cookieValue > 3) {\n          this.contactCard.classList.add(\"contact_card--no-display\");\n        } else {\n          (0, _utils.setCookie)(parseInt(cookieValue) + 1, \"contactCard\");\n          this.contactCard.classList.remove(\"contact_card--no-display\");\n          this.event();\n        }\n      } else {\n        (0, _utils.setCookie)(\"1\", \"contactCard\");\n        this.contactCard.classList.remove(\"contact_card--no-display\");\n        this.event();\n      }\n    }\n  }, {\n    key: \"event\",\n    value: function event() {\n      setTimeout(this.hideCard.bind(this), 20000);\n      this.contactCardClose.addEventListener(\"click\", this.hideCard.bind(this));\n    }\n  }, {\n    key: \"hideCard\",\n    value: function hideCard() {\n      this.contactCard.classList.add(\"contact_card--hide\");\n    }\n  }]);\n\n  return ContactCard;\n}();\n\nexports.default = ContactCard;\n\n//# sourceURL=webpack:///./assets/scripts/modules/app/_contact_card.js?");

/***/ }),

/***/ "./assets/scripts/modules/app/_image_viewer.js":
/*!*****************************************************!*\
  !*** ./assets/scripts/modules/app/_image_viewer.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\n\nvar _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nvar ImageViewer = function () {\n  function ImageViewer() {\n    _classCallCheck(this, ImageViewer);\n\n    this.modal = document.querySelector(\"#imageViewerModal\");\n\n    this.init();\n  }\n\n  _createClass(ImageViewer, [{\n    key: \"init\",\n    value: function init() {\n      document.querySelectorAll(\".image-viewer\").forEach(this.displayModalOnClick.bind(this));\n    }\n  }, {\n    key: \"displayModalOnClick\",\n    value: function displayModalOnClick(node) {\n      node.addEventListener(\"click\", this.displayModal.bind(this));\n    }\n  }, {\n    key: \"displayModal\",\n    value: function displayModal(e) {\n      this.modal.querySelector(\"img\").setAttribute(\"src\", e.target.getAttribute(\"src\"));\n      $(this.modal).modal();\n    }\n  }]);\n\n  return ImageViewer;\n}();\n\nexports.default = ImageViewer;\n\n//# sourceURL=webpack:///./assets/scripts/modules/app/_image_viewer.js?");

/***/ }),

/***/ "./assets/scripts/modules/app/_search_bar.js":
/*!***************************************************!*\
  !*** ./assets/scripts/modules/app/_search_bar.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\n\nvar _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nvar _headroom = __webpack_require__(/*! headroom.js */ \"./node_modules/headroom.js/dist/headroom.js\");\n\nvar _headroom2 = _interopRequireDefault(_headroom);\n\nfunction _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nvar SearchBar = function () {\n  function SearchBar() {\n    _classCallCheck(this, SearchBar);\n\n    this.body = document.querySelector(\"body\");\n    this.navbar = document.querySelector(\".navbar\");\n    this.navbarSupportedContent = document.querySelector(\".conceptos-navbar #navbarSupportedContent\");\n    this.categoriesMenu = document.querySelector(\"#categories-menu\");\n    this.modalCart = document.querySelector(\"#cartPreviewModal .modal-dialog\");\n    this.imageViewerModal = document.querySelector(\"#imageViewerModal .modal-dialog\");\n\n    this.productDialog = document.querySelector(\"#filterProductsModal .modal-dialog\");\n    this.searchBar = document.querySelector(\".search_bar\");\n\n    this.init();\n  }\n\n  _createClass(SearchBar, [{\n    key: \"init\",\n    value: function init() {\n      var headroom = new _headroom2.default(this.searchBar);\n      headroom.init();\n\n      var topDisplacement = this.searchBar.clientHeight + this.navbar.clientHeight;\n      var navbarDisplacement = this.navbar.clientHeight;\n\n      this.body.setAttribute(\"style\", \"top: \" + topDisplacement + \"px\");\n      this.searchBar.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n      this.navbarSupportedContent.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n      this.categoriesMenu.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n      this.modalCart.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n      this.imageViewerModal.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n\n      if (this.productDialog !== null) this.productDialog.setAttribute(\"style\", \"top: \" + navbarDisplacement + \"px\");\n    }\n  }, {\n    key: \"spaces\",\n    value: function spaces() {}\n  }]);\n\n  return SearchBar;\n}();\n\nexports.default = SearchBar;\n\n//# sourceURL=webpack:///./assets/scripts/modules/app/_search_bar.js?");

/***/ }),

/***/ "./assets/scripts/modules/app/_utils.js":
/*!**********************************************!*\
  !*** ./assets/scripts/modules/app/_utils.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.setCookie = setCookie;\nexports.getCookie = getCookie;\nexports.getCookieValue = getCookieValue;\nfunction setCookie(value) {\n  var name = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : \"products_cart\";\n  var days = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;\n\n  var date = new Date();\n  date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);\n  var expires = \"; expires=\" + date.toGMTString();\n  document.cookie = name + \"=\" + value + expires + \"; path=/\";\n}\n\nfunction getCookie(name) {\n  return document.cookie.includes(name);\n}\n\nfunction getCookieValue(name) {\n  var value = \";\" + document.cookie;\n  var parts = value.split(\";\" + name + \"=\");\n  if (parts.length === 2) {\n    return parts.pop().split(\";\").shift();\n  }\n}\n\n//# sourceURL=webpack:///./assets/scripts/modules/app/_utils.js?");

/***/ }),

/***/ "./node_modules/headroom.js/dist/headroom.js":
/*!***************************************************!*\
  !*** ./node_modules/headroom.js/dist/headroom.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("/*!\n * headroom.js v0.10.4 - Give your page some headroom. Hide your header until you need it\n * Copyright (c) 2020 Nick Williams - http://wicky.nillia.ms/headroom.js\n * License: MIT\n */\n\n(function (global, factory) {\n   true ? module.exports = factory() :\n  undefined;\n}(this, function () { 'use strict';\n\n  function isBrowser() {\n    return typeof window !== \"undefined\";\n  }\n\n  /**\n   * Used to detect browser support for adding an event listener with options\n   * Credit: https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener\n   */\n  function passiveEventsSupported() {\n    var supported = false;\n\n    try {\n      var options = {\n        // eslint-disable-next-line getter-return\n        get passive() {\n          supported = true;\n        }\n      };\n      window.addEventListener(\"test\", options, options);\n      window.removeEventListener(\"test\", options, options);\n    } catch (err) {\n      supported = false;\n    }\n\n    return supported;\n  }\n\n  function isSupported() {\n    return !!(\n      isBrowser() &&\n      function() {}.bind &&\n      \"classList\" in document.documentElement &&\n      Object.assign &&\n      Object.keys &&\n      requestAnimationFrame\n    );\n  }\n\n  function isDocument(obj) {\n    return obj.nodeType === 9; // Node.DOCUMENT_NODE === 9\n  }\n\n  function isWindow(obj) {\n    // `obj === window` or `obj instanceof Window` is not sufficient,\n    // as the obj may be the window of an iframe.\n    return obj && obj.document && isDocument(obj.document);\n  }\n\n  function windowScroller(win) {\n    var doc = win.document;\n    var body = doc.body;\n    var html = doc.documentElement;\n\n    return {\n      /**\n       * @see http://james.padolsey.com/javascript/get-document-height-cross-browser/\n       * @return {Number} the scroll height of the document in pixels\n       */\n      scrollHeight: function() {\n        return Math.max(\n          body.scrollHeight,\n          html.scrollHeight,\n          body.offsetHeight,\n          html.offsetHeight,\n          body.clientHeight,\n          html.clientHeight\n        );\n      },\n\n      /**\n       * @see http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript\n       * @return {Number} the height of the viewport in pixels\n       */\n      height: function() {\n        return win.innerHeight || html.clientHeight || body.clientHeight;\n      },\n\n      /**\n       * Gets the Y scroll position\n       * @return {Number} pixels the page has scrolled along the Y-axis\n       */\n      scrollY: function() {\n        if (win.pageYOffset !== undefined) {\n          return win.pageYOffset;\n        }\n\n        return (html || body.parentNode || body).scrollTop;\n      }\n    };\n  }\n\n  function elementScroller(element) {\n    return {\n      /**\n       * @return {Number} the scroll height of the element in pixels\n       */\n      scrollHeight: function() {\n        return Math.max(\n          element.scrollHeight,\n          element.offsetHeight,\n          element.clientHeight\n        );\n      },\n\n      /**\n       * @return {Number} the height of the element in pixels\n       */\n      height: function() {\n        return Math.max(element.offsetHeight, element.clientHeight);\n      },\n\n      /**\n       * Gets the Y scroll position\n       * @return {Number} pixels the element has scrolled along the Y-axis\n       */\n      scrollY: function() {\n        return element.scrollTop;\n      }\n    };\n  }\n\n  function createScroller(element) {\n    return isWindow(element) ? windowScroller(element) : elementScroller(element);\n  }\n\n  /**\n   * @param element EventTarget\n   */\n  function trackScroll(element, options, callback) {\n    var isPassiveSupported = passiveEventsSupported();\n    var rafId;\n    var scrolled = false;\n    var scroller = createScroller(element);\n    var lastScrollY = scroller.scrollY();\n    var details = {};\n\n    function update() {\n      var scrollY = Math.round(scroller.scrollY());\n      var height = scroller.height();\n      var scrollHeight = scroller.scrollHeight();\n\n      // reuse object for less memory churn\n      details.scrollY = scrollY;\n      details.lastScrollY = lastScrollY;\n      details.direction = scrollY > lastScrollY ? \"down\" : \"up\";\n      details.distance = Math.abs(scrollY - lastScrollY);\n      details.isOutOfBounds = scrollY < 0 || scrollY + height > scrollHeight;\n      details.top = scrollY <= options.offset;\n      details.bottom = scrollY + height >= scrollHeight;\n      details.toleranceExceeded =\n        details.distance > options.tolerance[details.direction];\n\n      callback(details);\n\n      lastScrollY = scrollY;\n      scrolled = false;\n    }\n\n    function handleScroll() {\n      if (!scrolled) {\n        scrolled = true;\n        rafId = requestAnimationFrame(update);\n      }\n    }\n\n    var eventOptions = isPassiveSupported\n      ? { passive: true, capture: false }\n      : false;\n\n    element.addEventListener(\"scroll\", handleScroll, eventOptions);\n    update();\n\n    return {\n      destroy: function() {\n        cancelAnimationFrame(rafId);\n        element.removeEventListener(\"scroll\", handleScroll, eventOptions);\n      }\n    };\n  }\n\n  function normalizeTolerance(t) {\n    return t === Object(t) ? t : { down: t, up: t };\n  }\n\n  /**\n   * UI enhancement for fixed headers.\n   * Hides header when scrolling down\n   * Shows header when scrolling up\n   * @constructor\n   * @param {DOMElement} elem the header element\n   * @param {Object} options options for the widget\n   */\n  function Headroom(elem, options) {\n    options = options || {};\n    Object.assign(this, Headroom.options, options);\n    this.classes = Object.assign({}, Headroom.options.classes, options.classes);\n\n    this.elem = elem;\n    this.tolerance = normalizeTolerance(this.tolerance);\n    this.initialised = false;\n    this.frozen = false;\n  }\n  Headroom.prototype = {\n    constructor: Headroom,\n\n    /**\n     * Start listening to scrolling\n     * @public\n     */\n    init: function() {\n      if (Headroom.cutsTheMustard && !this.initialised) {\n        this.addClass(\"initial\");\n        this.initialised = true;\n\n        // defer event registration to handle browser\n        // potentially restoring previous scroll position\n        setTimeout(\n          function(self) {\n            self.scrollTracker = trackScroll(\n              self.scroller,\n              { offset: self.offset, tolerance: self.tolerance },\n              self.update.bind(self)\n            );\n          },\n          100,\n          this\n        );\n      }\n\n      return this;\n    },\n\n    /**\n     * Destroy the widget, clearing up after itself\n     * @public\n     */\n    destroy: function() {\n      this.initialised = false;\n      Object.keys(this.classes).forEach(this.removeClass, this);\n      this.scrollTracker.destroy();\n    },\n\n    /**\n     * Unpin the element\n     * @public\n     */\n    unpin: function() {\n      if (this.hasClass(\"pinned\") || !this.hasClass(\"unpinned\")) {\n        this.addClass(\"unpinned\");\n        this.removeClass(\"pinned\");\n\n        if (this.onUnpin) {\n          this.onUnpin.call(this);\n        }\n      }\n    },\n\n    /**\n     * Pin the element\n     * @public\n     */\n    pin: function() {\n      if (this.hasClass(\"unpinned\")) {\n        this.addClass(\"pinned\");\n        this.removeClass(\"unpinned\");\n\n        if (this.onPin) {\n          this.onPin.call(this);\n        }\n      }\n    },\n\n    /**\n     * Freezes the current state of the widget\n     * @public\n     */\n    freeze: function() {\n      this.frozen = true;\n      this.addClass(\"frozen\");\n    },\n\n    /**\n     * Re-enables the default behaviour of the widget\n     * @public\n     */\n    unfreeze: function() {\n      this.frozen = false;\n      this.removeClass(\"frozen\");\n    },\n\n    top: function() {\n      if (!this.hasClass(\"top\")) {\n        this.addClass(\"top\");\n        this.removeClass(\"notTop\");\n\n        if (this.onTop) {\n          this.onTop.call(this);\n        }\n      }\n    },\n\n    notTop: function() {\n      if (!this.hasClass(\"notTop\")) {\n        this.addClass(\"notTop\");\n        this.removeClass(\"top\");\n\n        if (this.onNotTop) {\n          this.onNotTop.call(this);\n        }\n      }\n    },\n\n    bottom: function() {\n      if (!this.hasClass(\"bottom\")) {\n        this.addClass(\"bottom\");\n        this.removeClass(\"notBottom\");\n\n        if (this.onBottom) {\n          this.onBottom.call(this);\n        }\n      }\n    },\n\n    notBottom: function() {\n      if (!this.hasClass(\"notBottom\")) {\n        this.addClass(\"notBottom\");\n        this.removeClass(\"bottom\");\n\n        if (this.onNotBottom) {\n          this.onNotBottom.call(this);\n        }\n      }\n    },\n\n    shouldUnpin: function(details) {\n      var scrollingDown = details.direction === \"down\";\n\n      return scrollingDown && !details.top && details.toleranceExceeded;\n    },\n\n    shouldPin: function(details) {\n      var scrollingUp = details.direction === \"up\";\n\n      return (scrollingUp && details.toleranceExceeded) || details.top;\n    },\n\n    addClass: function(className) {\n      this.elem.classList.add(this.classes[className]);\n    },\n\n    removeClass: function(className) {\n      this.elem.classList.remove(this.classes[className]);\n    },\n\n    hasClass: function(className) {\n      return this.elem.classList.contains(this.classes[className]);\n    },\n\n    update: function(details) {\n      if (details.isOutOfBounds) {\n        // Ignore bouncy scrolling in OSX\n        return;\n      }\n\n      if (this.frozen === true) {\n        return;\n      }\n\n      if (details.top) {\n        this.top();\n      } else {\n        this.notTop();\n      }\n\n      if (details.bottom) {\n        this.bottom();\n      } else {\n        this.notBottom();\n      }\n\n      if (this.shouldUnpin(details)) {\n        this.unpin();\n      } else if (this.shouldPin(details)) {\n        this.pin();\n      }\n    }\n  };\n\n  /**\n   * Default options\n   * @type {Object}\n   */\n  Headroom.options = {\n    tolerance: {\n      up: 0,\n      down: 0\n    },\n    offset: 0,\n    scroller: isBrowser() ? window : null,\n    classes: {\n      frozen: \"headroom--frozen\",\n      pinned: \"headroom--pinned\",\n      unpinned: \"headroom--unpinned\",\n      top: \"headroom--top\",\n      notTop: \"headroom--not-top\",\n      bottom: \"headroom--bottom\",\n      notBottom: \"headroom--not-bottom\",\n      initial: \"headroom\"\n    }\n  };\n\n  Headroom.cutsTheMustard = isSupported();\n\n  return Headroom;\n\n}));\n\n\n//# sourceURL=webpack:///./node_modules/headroom.js/dist/headroom.js?");

/***/ })

/******/ });