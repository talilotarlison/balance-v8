    <section>
      <div class="container">
        <div class="masonry row">
          <div class="col s12">
            <h2>Dashboard</h2>
          </div>
          <div class="col l3 m6 s12">

            <div class="card green lighten-2">
              <div class="card-stacked">
                <div class="card-metrics card-metrics-static">
                  <div class="card-metric">
                    <div class="card-metric-title"><span>Entradas</span></div>
          <?php 

            $saldoInicialEntrada = 0;
            $arrEntrada = array();
            $buscarDadosEntrada = new SelectAll();
            $query = $buscarDadosEntrada -> selectDadosAll();
            $resultado = mysqli_query($conexao, $query);
            $row =  mysqli_num_rows($resultado);

            if($row ===0){
              echo "<div class='card-metric-value'>R$".$saldoInicialEntrada."</div>";
            }else{
              while ($linha = mysqli_fetch_assoc($resultado)) {
                if($linha['preco'] >= 0){
                  array_push($arrEntrada, $linha['preco']);
                }
              }
              $saldo_entrada = array_sum($arrEntrada);
              $_SESSION['entrada'] =  $saldo_entrada ;
              echo "<div class='card-metric-value'> R$ ".number_format($saldo_entrada , 2, ',', ' ')."</div>";
            }
          ?>         
           
                    <div class="card-metric-change increase">
                      <i class="material-icons left">arrow_upward</i>
                      <?= @$saldo_entrada > 0 ? number_format((($saldo_entrada/ $saldo_entrada)*100) , 2, ',', ' ') : 0  ?>  %
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="col l3 m6 s12">

            <div class="card  yellow lighten-3">
              <div class="card-stacked">
                <div class="card-metrics card-metrics-static">
                  <div class="card-metric">
                    <div class="card-metric-title"><span>Agendados</span></div>
          <?php 
            $saldoInicialAgendados = 0;
            $arrAgenda = array();
            $buscarDadosAgendados = new selectTipo( null, "Agendado");
            $query = $buscarDadosAgendados -> selectDadosTipo();
            $resultado = mysqli_query($conexao, $query);
            $row =  mysqli_num_rows($resultado);

            if($row === 0){
              echo "<div class='card-metric-value'>R$". $saldoInicialAgendados ."</div>";
            }else{
              while ($linha = mysqli_fetch_assoc($resultado)) {
                array_push($arrAgenda, $linha['preco']);
              }
              $saldo_agendado = array_sum($arrAgenda);
              $_SESSION['agendado'] =   $saldo_agendado;
              echo "<div class='card-metric-value'> R$ ".number_format($saldo_agendado , 2, ',', ' ')."</div>";
            }
            
          ?>

                    <div class="card-metric-change increase">
                      <i class="material-icons left">access_time</i>
                       <?= @$saldo_entrada > 0 ? number_format(((@$saldo_agendado/ $saldo_entrada)*100) , 2, ',', ' ') : 0 ?> %
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="col l3 m6 s12">

            <div class="card red lighten-2">
              <div class="card-stacked">
                <div class="card-metrics card-metrics-static">
                  <div class="card-metric">
                    <div class="card-metric-title"><span>Saidas</span></div>
          <?php 
            $saldoInicialSaidas = 0;
            $arrSaida = array();
            $buscarDadosSaida = new SelectAll();
            $query = $buscarDadosSaida -> selectDadosAll();
            $resultado = mysqli_query($conexao, $query);
            $row =  mysqli_num_rows($resultado);

            if($row ===0){
              echo "<div class='card-metric-value'>R$".$saldoInicialSaidas."</div>";
            }else{
              while ($linha = mysqli_fetch_assoc($resultado)) {
                if($linha['preco'] <= 0){
                  array_push($arrSaida, $linha['preco']);
                }
              }
              $saldo_saida = array_sum($arrSaida);
              $_SESSION['saida'] =  $saldo_saida;
              echo "<div class='card-metric-value'> R$ ".number_format($saldo_saida , 2, ',', ' ')."</div>";
            }
            
          ?>          

                    <div class="card-metric-change decrease">
                      <i class="material-icons left">arrow_downward</i>
                       <?= @$saldo_entrada > 0 ?  number_format((($saldo_saida/ $saldo_entrada)*100) , 2, ',', ' '): 0 ?> %
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="col l3 m6 s12">
            <div class="card  light-blue darken-2">
              <div class="card-stacked">
                <div class="card-metrics card-metrics-static">
                  <div class="card-metric">
                    <div class="card-metric-title"><span>Saldo Total</span></div>
          <?php
            $saldo_total = 0.0;
            $usuarioSaldoId = (int) ($_SESSION['idUser'] ?? 0);
            $stmtSaldoBancos = $conexao->prepare("SELECT COALESCE(SUM(CASE WHEN f.tipo = 'Entrada' THEN ABS(f.preco) WHEN f.tipo = 'Saida' THEN -ABS(f.preco) ELSE 0 END), 0) FROM bancos b LEFT JOIN fluxocaixa f ON f.banco_id = b.id WHERE b.usuario_id = ?");
            if ($stmtSaldoBancos) {
              $stmtSaldoBancos->bind_param('i', $usuarioSaldoId);
              if ($stmtSaldoBancos->execute()) {
                $stmtSaldoBancos->bind_result($saldo_total);
                $stmtSaldoBancos->fetch();
              }
              $stmtSaldoBancos->close();
            }
            $saldo_total = (float) $saldo_total;
            echo "<div class='card-metric-value'>R$ " . number_format($saldo_total, 2, ',', '.') . "</div>";
          ?>

                    <div class="card-metric-change decrease">
                      <i class="material-icons left">call_made</i>
                      <span>Saldo líquido nos bancos</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section>
