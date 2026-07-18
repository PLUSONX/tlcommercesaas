export default {
  currency(state) {
    return state.currency;
  },
  cartItemCount(state) {
    if (!Array.isArray(state.cart) || !state.cart.length) {
      return 0;
    }
    return state.cart.reduce(
      (total, item) => total + Number(item.quantity || 0),
      0
    );
  },
};
