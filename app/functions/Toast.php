<?php
  function printError($message, $status)
  {
      echo "
      <div  class='myToast mytoastDiv'>
              <div class='position-absolute shadow rounded-2 top-0 end-0 mt-3 me-3 bg-toast p-3 z-3'>
                <div class='toast-body p-2'> 
                  <p class='text-$status pt-3 me-3'>$message</p>
                  <span class='toast-btn onclick='closeMyToast();'><i class='fa-solid fa-xmark text-$status'></i></span>
                </div>
              </div>
            </div>
          ";
  }

  