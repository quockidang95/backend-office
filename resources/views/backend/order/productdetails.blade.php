@extends('layouts.admin')
@section('css')
  <style>
      *, *:before, *:after {
  box-sizing: border-box;
}
body {
  font-family: sans-serif;
}
@media (min-width: 600px) {
  body {
   
  }
}
.range-slider {
  margin: 10px 0 0 0%;
}
.range-slider {
  width: 100%;
}
.range-slider__range {
  -webkit-appearance: none;
  width: calc(100% - (73px));
  height: 10px;
  border-radius: 5px;
  background: #d7dcdf;
  outline: none;
  padding: 0;
  margin: 0;
}
.range-slider__range::-webkit-slider-thumb {
  appearance: none;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #2c3e50;
  cursor: pointer;
  transition: background 0.15s ease-in-out;
}
.range-slider__range::-webkit-slider-thumb:hover {
  background: #1abc9c;
}
.range-slider__range:active::-webkit-slider-thumb {
  background: #1abc9c;
}
.range-slider__range::-moz-range-thumb {
  width: 20px;
  height: 20px;
  border: 0;
  border-radius: 50%;
  background: #2c3e50;
  cursor: pointer;
  transition: background 0.15s ease-in-out;
}
.range-slider__range::-moz-range-thumb:hover {
  background: #1abc9c;
}
.range-slider__range:active::-moz-range-thumb {
  background: #1abc9c;
}
.range-slider__range:focus::-webkit-slider-thumb {
  box-shadow: 0 0 0 3px #fff, 0 0 0 6px #1abc9c;
}
.range-slider__value {
  display: inline-block;
  position: relative;
  width: 60px;
  color: #fff;
  line-height: 20px;
  text-align: center;
  border-radius: 3px;
  background: #2c3e50;
  padding: 5px 10px;
  margin-left: 8px;
}
.range-slider__value:after {
  position: absolute;
  top: 8px;
  left: -7px;
  width: 0;
  height: 0;
  border-top: 7px solid transparent;
  border-right: 7px solid #2c3e50;
  border-bottom: 7px solid transparent;
  content: '';
}
::-moz-range-track {
  background: #d7dcdf;
  border: 0;
}
input::-moz-focus-inner, input::-moz-focus-outer {
  border: 0;
}

  </style>
@endsection
@section('content')
<div class="row">
  <div class="col-lg-12">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('order.admin')}}">Danh sách sản phẩm</a></li>
              <li class="breadcrumb-item active font-weight-bold" aria-current="page">Thêm sản phẩm vào giỏ</li>
          </ol>
      </nav>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-6">
    <div class="media mt-4">
      <img class="pr-2 align-self-start" src="{{asset('source/images/' . $product->image)}}" alt="" width="160"
          height="160">
      <div class="media-body">
          <h5 class="mt-0">{{$product->name}}</h5>
      </div>
  </div>
  
  <div>
      <label for="status" class="font-weight-bold h4">Size:</label> <br>
      <input type="text" id="product_id" value="{{$product->id}}" hidden>
      @if ($product->price_L == null)
      <fieldset id="foobar" class="h4">
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input input-checked" id="{{$product->price . $product->id . ''}}"
                  name="price" value="{{$product->price}}">
              <label class="custom-control-label" for="{{$product->price . $product->id . ''}}"><span
                      class="badge badge-warning">{{number_format($product->price) . ' VNĐ'}}</span></label>
          </div>
      </fieldset>
      @else
      <fieldset id="foobar" class="h4">
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input input-checked" id="{{$product->price . $product->id . ''}}"
                  name="price" value="{{$product->price}}">
              <label class="custom-control-label" for="{{$product->price . $product->id . ''}}"><span
                      class="badge badge-warning">{{number_format($product->price) . ' VNĐ'}}</span></label>
          </div>
          <br>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input input-checked"
                  id="{{$product->price_L . $product->id . ''}}" name="price" value="{{$product->price_L}}">
              <label class="custom-control-label" for="{{$product->price_L . $product->id . ''}}"><span
                      class="badge badge-warning">{{number_format($product->price_L) . ' VNĐ'}}</span></label>
          </div>
          <br>
      </fieldset>
      @endif
      @if($recipes)
          @foreach ($recipes as $recipe)
          <div class="recipes mt-2">
          <span>{{ $recipe->name }}</span>
          <div class="range-slider">
              <input class="range-slider__range" type="range" value="100" min="0" max="200" step="20">
              <span class="range-slider__value">100</span>
            </div>
          </div>
          @endforeach
      @endif
      <div class="modal-footer mt-2 h4" style="position: relative">
          <div class="quantity buttons_added" style="position: absolute; left: 0">
              <input type="button" value="-" class="minus style rounded"><input type="number"
                  style="width: 25%; border: 0px solid #f6c23e; text-align: center" step="1" min="1" max=""
                  name="quantity" id="quantity" value="1" title="Qty" class="input-text qty text rounded" size="4"
                  pattern="" inputmode=""><input type="button" value="+" class="plus style rounded">
          </div>
      </div>
      <div class="mt-2 h4">
        <input type="button" readonly class="btn btn-warning font-weight-bold" name="total_price" id="total_price" value=""
            style="border: none;position: absolute; right: 5; width: 30%"><span>&nbsp;</span>
          </div>
      <form id="frmAddProduct" action="{{route('admin.cart.add')}}" method="post">
          @csrf
          <input type="text" name="p_quantity" hidden id="p_quantity" value="">
          <input type="text" name="p_price" hidden id="p_price" value="">
          <input type="text" name="p_name" hidden value="{{$product->name}}">
          <input type="text" name="p_id" id="p_id" hidden value="">
          <input type="text" name="p_recipe" id="p_recipe" hidden value="">
  
      </form>
  </div>
  </div>
</div>

@endsection
@section('script')
<script>
    var rangeSlider = function(){
  var slider = $('.range-slider'),
      range = $('.range-slider__range'),
      value = $('.range-slider__value');
    
  slider.each(function(){

    value.each(function(){
      var value = $(this).prev().attr('value');
      $(this).html(value + '%');
    });

    range.on('input', function(){
      $(this).next(value).html(this.value + '%');
    });
  });
};

rangeSlider();
</script>
<script>
    function wcqib_refresh_quantity_increments() {
        jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function(a, b) {
            var c = jQuery(b);
            c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minus" />'), c.children().last().after('<input type="button" value="+" class="plus" />')
        })
        }
        String.prototype.getDecimals || (String.prototype.getDecimals = function() {
        var a = this,
            b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
        return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
        }), jQuery(document).ready(function() {
        wcqib_refresh_quantity_increments()
        }), jQuery(document).on("updated_wc_div", function() {
        wcqib_refresh_quantity_increments()
        }), jQuery(document).on("click", ".plus, .minus", function() {
        var a = jQuery(this).closest(".quantity").find(".qty"),
            b = parseFloat(a.val()),
            c = parseFloat(a.attr("max")),
            d = parseFloat(a.attr("min")),
            e = a.attr("step");
        b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
        });
</script>


<script>
    $(document).on('change', 'input[type=radio][name=price]', function(){
        $('#p_price').val($(this).val());
        var number = Number($(this).val()) * Number($('#quantity').val())
        $('#total_price').val( new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(number));
    });
</script>
<script>
    $(document).ready(function(){

    $('#quantity').change(function(){
            var radioValue = $("input[name='price']:checked").val();
            var quantity = $(this).val();
            $('#p_quantity').val(quantity);
            var total_price = Number(radioValue) * Number(quantity);
            $('#total_price').val(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(total_price));
        
    });

});
</script>
<script>
    $(document).ready(function(){
        $('#total_price').click(function(){
            var recipes = $('.recipes').toArray();
            const arr = [];
            for(let i = 0; i < recipes.length; i++){
                arr.push({
                    name: recipes[i].childNodes[1].innerHTML,
                    value: recipes[i].childNodes[3].firstChild.nextSibling.value
                });         
            }
            const string = JSON.stringify(arr)
            $('#p_recipe').val(string);
            $('#p_quantity').val($('#quantity').val());
            var id = $('#product_id').val();
            var price =  $("input[name='price']:checked").val();
            var p_id = id + 'k' + price;
                $('#p_id').val(p_id);
                console.log(p_id);
            if( isNaN(parseInt($(this).val())) === true || $(this).val() == ''){
                alert('vui lòng chọn món!');
            }else{
                $('#frmAddProduct').submit();
            }
    });
})
</script>
@endsection
