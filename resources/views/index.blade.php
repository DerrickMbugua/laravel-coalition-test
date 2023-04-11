<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel Coalition Test</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
  <style>
   .error{ color:red; } 
  </style>
 
</head>
  
<body>
  
<div class="container">
    <h2 style="margin-top: 20px;">Products</h2>
    <br>
    
    <form id="save_products" method="post" action="javascript:void(0)">
      @csrf
      <div class="row mb-3">
        <div class="col">
          <label>Product Name</label>
          <input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Please enter name">
          <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
        <div class="col">
          <label>Quantity</label>
          <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Please enter quantity">
          <span class="text-danger">{{ $errors->first('quantity') }}</span>
        </div>

      </div>
      <div class="row mb-3">
        <div class="col">
          <label>Price</label>
          <input type="number" name="price" class="form-control" id="price" placeholder="Please enter price">
          <span class="text-danger">{{ $errors->first('price') }}</span>
        </div>
        <div class="col">

        </div>

      </div>
      {{-- <div class="alert alert-success d-none" id="msg_div">
        <span id="res_message"></span>
      </div> --}}
      <div class="form-group">
       <button type="submit" id="send_form" class="btn btn-success">Submit</button>
      </div>
     
    </form>

    <div style="margin-top: 50px;">
      <a href="/" class="btn btn-primary" style="margin-bottom: 30px;">REFRESH</a>
      <h3 style="margin-bottom: 30px;">All Products</h3>
      <table class="table" id="tbl">
        {{-- <thead> --}}
          <tr>
            <th scope="col">Product Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            <th scope="col">Date</th>
            <th scope="col">Total</th>
          </tr>
        {{-- </thead> --}}
        {{-- <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
          </tr>
         
        </tbody> --}}
      </table>

      <script>
        $.ajax({
              url:'http://localhost:8000/storage/products.json',
              dataType:'json',
              data:{},
              success: function(data){
                  for(var i=0; i<data.length; i++){
                      var row=$('<tr><td>' + data[i].name + '</td><td>' + data[i].quantity + '</td><td>' + data[i].price + '</td><td>' + data[i].date + '</td><td>' + data[i].total + '</td></tr>');
                      $('#tbl').append(row);
                  }
              },
              error: function(jqXHR, textStatus, errorThrown){
                  console.log('Error:'+textStatus+'-'+errorThrown);
              }
          });
      </script>
    </div>
   
  
</div>

<script>
   if ($("#save_products").length > 0) {
    $("#save_products").validate({
      
    rules: {
      name: {
        required: true,
      },
  
       price: {
            required: true,
            digits:true,
        },
        quantity: {
                required: true,
                digits:true,
            },    
    },
    messages: {
        
      name: {
        required: "Please enter name",
      },
      price: {
        required: "Please enter price",
        digits: "Please enter only numbers",
      },
      quantity: {
          required: "Please enter quantity",
          digits: "Please enter only numbers",
        
        },
         
    },
    submitHandler: function(form) {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('#send_form').html('Sending..');
      $.ajax({
        url: 'http://localhost:8000/save-product' ,
        type: "POST",
        data: $('#save_products').serialize(),
        success: function( response ) {
            $('#send_form').html('Submit');
            $('#res_message').show();
            $('#res_message').html(response.msg);
            $('#msg_div').removeClass('d-none');
 
            document.getElementById("save_products").reset(); 
            setTimeout(function(){
            $('#res_message').hide();
            $('#msg_div').hide();
            },10000);
        }
      });
    }
  })
}
</script>
</body>
</html>