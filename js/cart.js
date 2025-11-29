(function(){
  const base = '../actions/';

  async function post(url, data){
    const res = await fetch(url, { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams(data).toString() });
    return res.json();
  }

  window.Cart = {
    async add(product_id, qty=1){
      return post(base + 'add_to_cart_action.php', {product_id, qty});
    },
    async remove(product_id){
      return post(base + 'remove_from_cart_action.php', {product_id});
    },
    async update(product_id, qty){
      return post(base + 'update_quantity_action.php', {product_id, qty});
    },
    async empty(){
      return post(base + 'empty_cart_action.php', {});
    }
  };
})();
