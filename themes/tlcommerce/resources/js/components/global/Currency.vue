<template>
  <component v-bind:is="tag">{{ formattedCurrencyValue }}</component>
</template>
<script>
import { mapState } from "vuex";
import { safeJsonParse } from "../../utils/safeStorage";

const EMPTY_CURRENCY = {
  symbol: "",
  conversion_rate: 1,
  position: 1,
  thousand_separator: ",",
  decimal_separator: ".",
  number_of_decimal: 2,
};

function readCurrency(key) {
  const parsed = safeJsonParse(key, null);
  if (!parsed || typeof parsed !== "object") {
    return { ...EMPTY_CURRENCY };
  }
  return {
    ...EMPTY_CURRENCY,
    ...parsed,
    conversion_rate: Number(parsed.conversion_rate) || 1,
    number_of_decimal:
      parsed.number_of_decimal != null ? Number(parsed.number_of_decimal) : 2,
  };
}

export default {
  name: "Currency",
  props: {
    amount: {
      type: Number,
      required: false,
    },
    tag: {
      type: String,
      required: false,
      default: "span",
    },
  },
  data() {
    return {
      system_currency: readCurrency("default_currency"),
      user_currency: readCurrency("currency"),
    };
  },
  computed: mapState({
    formattedCurrencyValue() {
      const thousand_separator =
        this.user_currency.thousand_separator != null
          ? this.user_currency.thousand_separator
          : ",";
      const decimal_separator =
        this.user_currency.decimal_separator != null
          ? this.user_currency.decimal_separator
          : ".";

      let converted_amount = 0;
      if (typeof this.amount == "undefined") {
        converted_amount = 0;
      } else {
        converted_amount = this.amount;
      }
      converted_amount =
        converted_amount != 0
          ? (this.amount * this.user_currency.conversion_rate) /
            this.system_currency.conversion_rate
          : 0;

      converted_amount = parseFloat(converted_amount).toFixed(
        this.user_currency.number_of_decimal
      );
      var amount_parts = converted_amount.toString().split(".");
      const numberPart = amount_parts[0];
      const decimalPart = amount_parts[1];
      const thousands = /\B(?=(\d{3})+(?!\d))/g;

      let integer_part = numberPart.replace(thousands, thousand_separator);
      let decimal_part = decimalPart ? decimal_separator + decimalPart : "";

      let final_amount = integer_part + decimal_part;

      if (this.user_currency.position == 1) {
        return this.user_currency.symbol + "" + final_amount;
      }

      if (this.user_currency.position == 2) {
        return final_amount + "" + this.user_currency.symbol;
      }

      if (this.user_currency.position == 3) {
        return this.user_currency.symbol + " " + final_amount;
      }

      if (this.user_currency.position == 4) {
        return final_amount + " " + this.user_currency.symbol;
      }

      return final_amount;
    },
  }),
};
</script>
