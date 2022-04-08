
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
function thousands_separators(num){
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
  }
</script>	
    </body>
</html>