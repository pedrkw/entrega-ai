<?php include_once __DIR__ . '/../includes/menu.php'; ?>
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-xl-12 col-lg-12 col-md-9">
             <div class="card o-hidden border-0 my-5">
                <div class="card-body p-0">
                   <div class="row">
                      <div class="col-lg-12">
                         <div class="p-3 mb-4 ">

                            <?php if(!isset($delivery_id)){ ?>

                            <div class="d-sm-flex align-items-center justify-content-between">
                               <h1 class="h3 mb-4 text-gray-800">Rastreie sua encomenda 😊</h1>
                               <a href="<?= BASE_URL ?>/usuario/historico" class="site-btn">Voltar</a>
                            </div>
                               <p class="h5 mb-4 text-gray-800">Me informa o identificador do pedido.</p>
                            
                                <div class="col-lg-12 text-center" style="margin: auto; margin-top: 20px;" id="collapseRastreio">
                                      <div class="card-body">
                                        <form class="user" action="<?= BASE_URL ?>usuario/rastrear" method="post" id="searchForm">
                                           <div class="form-group">
                                             <input type="text" class="form-control form-control-user" name="delivery_id" id="delivery_id" placeholder="Código de rastreio" value="<?= (isset($delivery_id) ? $delivery_id : ""); ?>" 
                                      aria-label="Search" aria-describedby="basic-addon2" required>
                                    
                                           </div>
                                           <button type="submit" class="btn btn-primary btn-user btn-block"><span><i class="fa fa-search"></i> Rastrear</span></button>
                                        </form>
                                      </div>
                                </div>

                            <?php }else{ ?>

                               <div class="container">
                                    <div class="d-sm-flex align-items-center justify-content-between mt-4">
                                        <h1 class="h3 mb-0 text-gray-800">Encomenda #<?= $delivery_id; ?></h1>
                                        <a href="<?= BASE_URL ?>usuario/historico" class="btn btn-secondary">Voltar</a>
                                    </div>
                                    
                                    <p class="h5 mt-4 text-gray-800">Acompanhe onde está sua encomenda</p>
                                    
                                    <div class="row justify-content-center mt-4">
                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="map" style="height: 400px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <?php } ?>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>

    <script>
          document.getElementById("searchForm").addEventListener("submit", function (event) {
              event.preventDefault();
              
              var delivery_id = document.getElementById("delivery_id").value;
              window.location.href = "<?= BASE_URL; ?>usuario/rastrear/delivery/" + encodeURIComponent(delivery_id);
          });
    </script>

    <script>
   function initMap() {
            var deliveryPoints = [
                <?php foreach ($delivery as $deliveries): ?>
                    {
                        lat: <?= $deliveries->getCurrent_latitude(); ?>,
                        lng: <?= $deliveries->getCurrent_longitude(); ?>
                    },
                <?php endforeach; ?>
            ];

            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            var map = new google.maps.Map(document.getElementById('map'), {
                center: deliveryPoints[0], // Usando o primeiro ponto como ponto central inicial
                zoom: 12
            });
            directionsRenderer.setMap(map);

            var waypoints = [];
            for (var i = 1; i < deliveryPoints.length - 1; i++) {
                waypoints.push({
                    location: new google.maps.LatLng(deliveryPoints[i].lat, deliveryPoints[i].lng),
                    stopover: true
                });
            }

            var start = new google.maps.LatLng(deliveryPoints[0].lat, deliveryPoints[0].lng); // Coordenadas iniciais
            var end = new google.maps.LatLng(deliveryPoints[deliveryPoints.length - 1].lat, deliveryPoints[deliveryPoints.length - 1].lng); // Coordenadas do último ponto

            var request = {
                origin: start,
                destination: end,
                waypoints: waypoints,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING // Pode ser WALKING, BICYCLING, ou outros
            };

            directionsService.route(request, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    window.alert('Não foi possível calcular a rota: ' + status);
                }
            });
        }

    </script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>