<template>
  <div>
    <heading class="mb-6">Margins Calculator</heading>

    <card class="bg-light" style="min-height: 300px;">
      <div class="card-body">
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="usdPrice" class="inline-block text-80 pt-2 leading-tight">USD Price</label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <input class="w-full form-control form-input form-input-bordered" id="usdPrice" v-model="usdPrice">
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="is_round" class="inline-block text-80 pt-2 leading-tight">
              Stone Shape
            </label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <select class="w-full form-control form-select form-input-bordered" id="is_round" v-model="isRound">
              <option value="1">Round</option>
              <option value="0">Other</option>
            </select>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="manId" class="inline-block text-80 pt-2 leading-tight">Manufacturer</label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <select class="w-full form-control form-select form-input-bordered" id="manId" v-model="manufacturer">
              <option value="0">Master</option>
              <option v-for="(man, idx) in getManufacturers" :key="idx" :value="man.id">
                {{ man.title }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="caratRange" class="inline-block text-80 pt-2 leading-tight">Carat range</label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <select class="w-full form-control form-select form-input-bordered" id="caratRange" v-model="caratRange">
              <option v-for="(range, idx) in getCaratRanges" :key="idx" :value="range.carat_range">
                {{ range.carat_range }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="color" class="inline-block text-80 pt-2 leading-tight">Color</label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <select class="w-full form-control form-select form-input-bordered" id="color" v-model="color">
              <option v-for="color in getColors" :key="color.id" :value="color.id">
                {{ color.title }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <label for="clarity" class="inline-block text-80 pt-2 leading-tight">Clarity</label>
          </div>
          <div class="py-2 px-8 w-1/2">
            <select class="w-full form-control form-select form-input-bordered" id="clarity" v-model="clarity">
              <option v-for="clarity in getClarities" :key="clarity.id" :value="clarity.id">
                {{ clarity.title }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 pt-2 leading-tight">Margin</div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-80 pt-2 leading-tight">{{ getMargin }}%</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 pt-2 leading-tight">Exchange (USD to AUD)</div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-80 pt-2 leading-tight">{{ getCurrency }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 pt-2 leading-tight">Global price</div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-80 pt-2 leading-tight">A${{ subtotalWithoutUpdates }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 leading-tight">Global margin: </div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-danger leading-tight"><input class="form-control form-input form-input-bordered w-1/4" v-model="incP">% / A${{ amountIncPrice }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 leading-tight">Discount: </div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-danger leading-tight"><input class="form-control form-input form-input-bordered w-1/4" v-model="soldP">% / A${{ amountSoldPrice }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 pt-2 leading-tight">Price exc GST</div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-80 leading-tight">A${{ subtotalPrice }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 leading-tight">GST</div>
          </div>
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 leading-tight">{{ getGst }}% / A${{ amountGst }}</div>
          </div>
        </div>
        <div class="flex border-b border-40">
          <div class="w-1/4 px-8 py-2">
            <div class="inline-block text-80 pt-2 leading-tight">Total Price inc GST</div>
          </div>
          <div class="py-2 px-8 w-1/2">
            <div class="inline-block text-80 pt-2 leading-tight">A$<strong>{{ totalPrice }}</strong></div>
          </div>
        </div>
      </div>
    </card>
  </div>
</template>

<script>
import {
  mapGetters,
  mapActions,
} from 'vuex';
import store from '../store';

export default {
  metaInfo() {
    return {
      title: 'Margins Calculator',
    }
  },
  created() {
    this.$store.registerModule('base', store);
  },
  destroyed() {
    this.$store.unregisterModule('base');
  },
  computed: {
    ...mapGetters([
      'getColors',
      'getClarities',
      'getCaratRanges',
      'getCurrency',
      'getGst',
      'getMargin',
      'getIncreasePercent',
      'getSoldPercent',
      'getManufacturers'
    ]),
    subtotalWithoutUpdates() {
      return this.round((this.usdPrice / this.getCurrency) * (1 + this.getMargin / 100));
    },
    subtotalPrice() {
      return this.round(this.subtotalWithoutUpdates * this.incRate * this.soldRate);
    },
    totalPrice() {
      return this.round(this.subtotalPrice * (1 + this.getGst / 100));
    },
    amountIncPrice() {
      return this.round(this.subtotalWithoutUpdates * (1 - this.incRate)) * -1;
    },
    amountSoldPrice() {
      return this.round(this.subtotalWithoutUpdates * this.incRate * (1 - this.soldRate));
    },
    amountGst() {
      return this.round(this.totalPrice - this.subtotalPrice);
    },
    incRate() {
      return 1 + this.incP / 100
    },
    soldRate() {
      return 1 - this.soldP / 100
    },
    incP() {
      return this.toPercent(this.getIncreasePercent)
    },
    soldP() {
      return this.toPercent(this.getSoldPercent)
    }
  },
  methods: {
    ...mapActions(['loadData', 'getMarginPercent']),
    marginRequest() {
      this.getMarginPercent({
        range: this.caratRange,
        colorId: this.color,
        clarityId: this.clarity,
        isRound: this.isRound,
        manId: this.manufacturer
      });
    },
    round(val) {
      return val.toFixed(2);
    },
    toPercent(val) {
      return Math.abs((1 - val) * 100).toFixed(2)
    }
  },
  data() {
    return {
      usdPrice: 0,
      caratRange: null,
      color: null,
      clarity: null,
      isRound: 1,
      manufacturer: 0,
    }
  },
  mounted() {
    this.loadData();
  },
  watch: {
    caratRange: function (newVal, oldVal) {
      this.marginRequest();
    },
    color: function (newVal, oldVal) {
      this.marginRequest();
    },
    clarity: function (newVal, oldVal) {
      this.marginRequest();
    },
    isRound: function (newVal, oldVal) {
      this.marginRequest();
    },
    manufacturer: function (newVal, oldVal) {
      this.marginRequest();
    }
  }
}
</script>

<style>
/* Scoped Styles */
</style>
