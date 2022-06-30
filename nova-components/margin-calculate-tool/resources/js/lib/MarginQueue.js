export default class MarginQueue {
  constructor() {
    this._queue = new Map();
    this._errorMessageCode = '';
    this._subscribeList = [];
  }

  addMargin(marginKey, marginObj) {
    this._validateMargin(marginObj);

    if (this._errorMessageCode) {
      throw new Error(this.getErrorMessageCode());
    }

    this._addOrDeleteOnSimilar(marginKey, marginObj);

    this._runSubscribers('change');
  }

  clearMargins() {
    this._queue.clear();

    this._runSubscribers('change');
  }

  /**
   * Check when user return old value to margin entity. If it true, we need to remove this entity from queue that will
   * be send to server for saving.
   *
   * @param marginKey
   * @param marginObj
   * @private
   */
  _addOrDeleteOnSimilar(marginKey, marginObj) {
    const prevMarginObj = this._queue.get(marginKey);

    if (prevMarginObj && prevMarginObj.previous_margin === marginObj.margin) {
      this._queue.delete(marginKey);

      return;
    }

    if (prevMarginObj) {
      marginObj.previous_margin = prevMarginObj.previous_margin;
    }

    this._queue.set(marginKey, marginObj);
  }

  getMarginList() {
    return this._queue;
  }

  _validateMargin(marginObj) {
    this._errorMessageCode = '';

    for (let i = 0; i < MarginQueue.getMarginTypes().length; i++) {
      const type = MarginQueue.getMarginTypes()[i];

      if (!_.has(marginObj, type.key)) {
        this._errorMessageCode = 'missing_margin_key';
        break;
      }

      const marginValue = marginObj[type.key];
      const hasValidType = type.type
        .map((typeVariant) => {
          if (typeVariant === null && marginValue === typeVariant) {
            return true;
          }

          if ([undefined, null].indexOf(marginValue) !== -1) {
            return false;
          }

          return marginValue.constructor === typeVariant;
        })
        .filter((typeIsValid) => typeIsValid)
        .length > 0;

      if (!hasValidType) {
        this._errorMessageCode = 'invalid_margin_type';
        break;
      }
    }
  }

  getErrorMessageCode() {
    return this._errorMessageCode;
  }

  subscribeOn(eventName, callback) {
    if (typeof callback !== 'function') {
      return;
    }

    this._subscribeList.push({
      eventName,
      callback,
    });
  }

  _runSubscribers(eventName) {
    this._subscribeList
      .filter((subscriber) => subscriber.eventName === eventName)
      .forEach((subscriber) => subscriber.callback(this.getMarginList()));
  }

  static getMarginTypes() {
    return [
      {
        key: 'key',
        type: [String],
      },
      {
        key: 'manufacturer',
        type: [null, String],
      },
      {
        key: 'carat_min',
        type: [String, Number],
      },
      {
        key: 'carat_max',
        type: [String, Number],
      },
      {
        key: 'is_round',
        type: [Boolean, String, Number],
      },
      {
        key: 'clarity',
        type: [String],
      },
      {
        key: 'color',
        type: [String],
      },
      {
        key: 'margin',
        type: [Number, String],
      },
      {
        key: 'previous_margin',
        type: [Number, String],
      },
    ];
  }
}