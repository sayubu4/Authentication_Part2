(function(){
  async function processCheckout(){
    // Simulated payment confirmation modal (simple confirm for demo)
    const ok = window.confirm('Simulate payment? Click OK to confirm.');
    if (!ok) return alert('Payment cancelled.');

    const res = await fetch('../actions/process_checkout_action.php', { method:'POST', credentials:'same-origin' });
    const data = await res.json();
    if (data.status === 'success') {
      alert('Reservation confirmed!\nOrder #' + data.order_id + '\nRef: ' + data.invoice_no + '\nAmount: ' + data.amount + ' ' + data.currency);
      window.location.href = 'cart.php';
    } else {
      alert('Failed: ' + (data.message || 'Unknown error'));
    }
  }

  window.Checkout = { process: processCheckout };
})();
