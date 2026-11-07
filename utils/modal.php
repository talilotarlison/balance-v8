<script type="text/javascript">
  // Modal de exluir itens do site
     document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
  });
  
  // Sidenav
  document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });

  // Modal do site
  document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, {
      // Opções personalizadas
      inDuration: 500, // Duração da animação de entrada em milissegundos
      outDuration: 250, // Duração da animação de saída em milissegundos
      opacity: 0.5, // Opacidade do fundo
      onOpenStart: function () { // Função de callback antes de abrir o modal
        console.log('Modal aberto');
      },
      onCloseEnd: function () { // Função de callback depois de fechar o modal
        console.log('Modal fechado');
      }
    });
  });
  
</script>


