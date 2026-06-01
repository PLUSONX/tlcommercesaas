export default {
  currency(state) {
    return state.currency;
  },
  cartItemCount(state) {
    if (!state.cart.length) {
      return 0;
    }
    return state.cart.reduce(
      (total, item) => total + Number(item.quantity || 0),
      0
    );
  },
};
