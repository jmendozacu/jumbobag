var Innersense = Innersense || {};
/**
 * Available options:
 *  - viewerId: id of the viewer container DOM element
 *  - viewerUrl: url of the viewer to load in iframe
 *  - debug: whether to show debugger and debug logs (default: false)
 */

var supportsViewer = function supportsViewer() {
  // https://github.com/mrdoob/three.js/blob/3d8e0d43c6b79468a585cb37c63c3692a58125dc/examples/js/Detector.js#L11
  try {
    var canvas = document.createElement("canvas");
    return (
      !!window.WebGLRenderingContext &&
      (canvas.getContext("webgl") || canvas.getContext("experimental-webgl"))
    );
  } catch (e) {
    return false;
  }
};

Innersense.isSupported = supportsViewer();

Innersense.Viewer3D = function(options) {
  var currentOptions = {};
  var currentPrice = null;
  var lastSentOption = null;
  var initialized = false;
  var initializeOnLoad =
    typeof options.initializeOnLoad === "undefined"
      ? false
      : options.initializeOnLoad;
  var viewer, viewerDebugger;
  var isWaitingReturnTimeout, isWaitingResponse;

  if (!options.initOptions) {
    options.initOptions = {
      hiddenMenu: true
    };
  }

  var initialize = function initialize() {
    if (viewer) {
      console.warn("3DViewer: already initialized");
      return;
    }
    if (options.debug) {
      console.log("3DViewer: Initializing Innersense3DViewer");
    }

    viewer = document.createElement("iframe");
    viewer.allowtransparency = "true";
    viewer.className = "viewer-3d__viewer";
    viewer.addEventListener("load", loadView);
    viewer.src = options.viewerUrl;

    document.getElementById(options.viewerId).appendChild(viewer);
    viewerDebugger = attachDebugger(viewer);
  };

  var changePrice = function changePrice(newPrice) {
    viewerDebugger.setStatus("changing price to " + newPrice);
    if (options.debug) {
      console.log("3DViewer: update price triggered. DATA", {
        type: "updatePrice",
        price: newPrice
      });
    }
    currentPrice = newPrice;

    if (initialized) {
      viewer.contentWindow.postMessage(
        {
          type: "updatePrice",
          price: newPrice
        },
        "*"
      );
    }
  };

  function createNewOption(optionId, optionValue, optionType) {
    return {
      id: optionId,
      value: optionValue,
      type: optionType,
      done: false
    };
  }

  var selectOption = function selectOption(optionId, optionValue, optionType) {
    optionType = optionType || "selectShade";
    if (
      !currentOptions[optionId] ||
      currentOptions[optionId].value !== optionValue
    ) {
      currentOptions[optionId] = createNewOption(
        optionId,
        optionValue,
        optionType
      );
      updateQueuedOptions();
    }
  };

  var attachDebugger = function attachDebugger(viewer) {
    if (!options.debug) {
      return {
        setStatus: function() {}
      };
    }

    var makeButton = function makeButton(label, className) {
      var button = document.createElement("button");
      button.className = className || "viewer-3d__debugger__action";
      button.innerText = label;
      return button;
    };
    var viewerDebugger = document.createElement("div");
    var status = document.createElement("p");
    var selectOptionButton = makeButton("Définir une option");
    var updatePriceButton = makeButton("Modifier le prix");
    var toggleDebuggerButton = makeButton(
      "Afficher / Cacher le débogueur",
      "viewer-3d__toggle-debugger"
    );

    viewerDebugger.className = "viewer-3d__debugger";
    status.className = "viewer-3d__debugger__status";

    viewerDebugger.appendChild(status);
    viewerDebugger.appendChild(selectOptionButton);
    viewerDebugger.appendChild(updatePriceButton);
    viewer.parentNode.insertBefore(viewerDebugger, viewer.nextSibling);
    viewerDebugger.parentNode.insertBefore(
      toggleDebuggerButton,
      viewerDebugger.nextSibling
    );

    selectOptionButton.addEventListener("click", function() {
      var optionId = window.prompt("ID Option Magento", 42229);
      var optionValue = window.prompt("Id de la valeur Magento", 395563);
      var optionType = window.prompt("Type d'option", "selectShade");

      if (currentOptions[optionId]) {
        delete currentOptions[optionId];
      }
      selectOption(optionId, optionValue, optionType);
    });
    updatePriceButton.addEventListener("click", function() {
      var newPrice = window.prompt("New price: ", 999.99);
      changePrice(newPrice);
    });

    toggleDebuggerButton.addEventListener("click", function() {
      var isDisplayed = viewerDebugger.style.display === "block";
      viewerDebugger.style.display = isDisplayed ? "none" : "block";
    });

    return {
      setStatus: function(message) {
        status.innerText = message;
      }
    };
  };

  var loadView = function loadView() {
    if (initializeOnLoad) {
      initialized = true;
    }
    viewer.contentWindow.postMessage(
      Object.assign({ type: "init" }, options.initOptions),
      "*"
    );
    viewerDebugger.setStatus("initialisation demandée");

    window.addEventListener("message", onViewerMessageReceived, false);
  };

  var onViewerMessageReceived = function onViewerMessageReceived(event) {
    var action = event.data;
    if (options.debug) {
      console.log("3DViewer: message received. ACTION", action);
    }

    switch (action.type) {
      case "furnitureLoaded":
        viewerDebugger.setStatus("furniture loaded");
        if (!initializeOnLoad) {
          initialized = true;
        }
        if (currentPrice) changePrice(currentPrice);
        updateQueuedOptions();
        break;
      case "shadeUpdated":
        viewerDebugger.setStatus("shade updated");
        break;
      case "accessoryUpdated":
        viewerDebugger.setStatus("accessory updated");
        break;
      case "successfullyUpdated":
        viewerDebugger.setStatus("successfully updated");
        isWaitingResponse = false;
        if (isWaitingReturnTimeout) clearTimeout(isWaitingReturnTimeout);
        break;
      case "badRequest":
        viewerDebugger.setStatus("bad request");
        isWaitingResponse = false;
        if (options.debug && isWaitingReturnTimeout)
          clearTimeout(isWaitingReturnTimeout);
    }
  };

  var updateQueuedOptions = function updateQueuedOptions() {
    if (initialized && currentOptions) {
      var option = null;
      for (var key in currentOptions) {
        if (currentOptions.hasOwnProperty(key) && !currentOptions[key].done) {
          option = currentOptions[key];
          break;
        }
      }

      if (!option) {
        return;
      }

      if (options.debug) {
        console.log("3DViewer: update option triggered. DATA", {
          type: option.type,
          idOption: option.id,
          idValue: option.value
        });
        isWaitingReturnTimeout = setTimeout(function() {
          console.warn("3DViewer: no return message received for ", {
            type: option.type,
            idOption: option.id,
            idValue: option.value
          });
        }, 3000);
      }

      isWaitingResponse = true;
      viewer.contentWindow.postMessage(
        {
          type: option.type,
          idOption: option.id,
          idValue: option.value
        },
        "*"
      );

      currentOptions[option.id].done = true;
      updateQueuedOptions();
    }
  };

  return {
    initialize: initialize,
    changePrice: changePrice,
    selectOption: selectOption
  };
};
