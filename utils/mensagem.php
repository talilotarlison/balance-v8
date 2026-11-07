<script type="text/javascript">
    // script para de mensagem do usuario
    window.onload = ()=> {
      var toastHTML = '<span><?= $_SESSION['mensagem']?></span><button class="btn-flat toast-action"> <i class="material-icons">close</i></button>';
      M.toast({html: toastHTML});
    // Coloque sua lógica aqui
    }
</script>

