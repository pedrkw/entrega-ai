<?php include_once __DIR__ . '/../includes/menu.php'; ?>
      
      
      <div class="container">
         <div class="col-lg-12">
            <div class="p-3 mb-4 mt-4">
               <div class="d-sm-flex align-items-center justify-content-between">
                  <h1 class="h3 mb-4 text-gray-800">Oi, <?= $_SESSION['first_name']; ?>! 😊</h1>
               </div>
            </div>
         </div>
         <div class="view-account">
            <section class="module">
               <div class="module-inner">
                  <?php include_once __DIR__ . '/../includes/sideMenu.php'; ?>
                  <div class="content-panel">
                     <h2 class="title">Mudar senha</h2>
                     <div class="mb-4 mt-4">
                        <?= SessionMessage(); ?>
                     </div>
                     <div class="billing">
                        <form action="<?= BASE_URL ?>usuario/senha/update" method="post">
                           <div class="form-group">
                              <label class="control-label" for="oldPassword">Senha atual:</label>
                              <input type="password" class="styles__Field-tg3uj4-1 gXNzQk" name="oldPassword" id="oldPassword" placeholder="Senha atual" required>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-6 mb-3 mb-sm-0">
                                 <label class="control-label" for="password">Nova senha:</label>
                                 <input type="password" class="styles__Field-tg3uj4-1 gXNzQk" name="password" id="password" placeholder="Nova senha" required >
                              </div>
                              <div class="col-sm-6">
                                 <label class="control-label" for="repeatPassword">Repita a nova senha:</label>
                                 <input type="password" class="styles__Field-tg3uj4-1 gXNzQk" name="repeatPassword" id="repeatPassword" placeholder="Repita a senha" required>
                              </div>
                           </div>
                           <input type="hidden" name="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                           <button type="submit" class="btn site-btn"><i class="fa fa-save"></i>  Atualizar</button>
                        </form>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <script type="text/javascript">
         
         $(document).ready(function() {
          
            $('#user-form-edit').submit(function(event) {
               
               event.preventDefault();

               // Check if any required fields are empty
               var name = $('#name').val().trim();
               var phone = $('#phone').val().trim();
               var email = $('#email').val().trim();
               var birthdate = $('#birthdate').val().trim();

               if (name === '' || phone === '' || birthdate === '') {
                  Swal.fire({
                     icon: 'error',
                     title: 'Erro!',
                     text: 'Preencha todos os campos obrigatórios.',
                  });
                  return;
               }

               var formData = $(this).serialize();

               $.ajax({
                  type: 'POST',
                  url: '<?= BASE_URL ?>usuario/update',
                  data: formData,
                  
                  success: function(response) {
               
                     if (response.success) {
                        Swal.fire({
                           icon: 'success',
                           title: 'Sucesso!',
                           text: 'Os seus dados foram atualizados!',
                        });
                     } else {
                        Swal.fire({
                           icon: 'error',
                           title: 'Erro!',
                           text: 'Não foi possível atualizar seus dados. Tente novamente!',
                        });
                     }

                  },

               });
            
            });
         
         });

      </script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>