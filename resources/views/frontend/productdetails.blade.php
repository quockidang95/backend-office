@extends('layouts.customer')

@section('customer')


<div class="media mt-4">
    <img class="pr-2 align-self-start" src="{{asset('source/images/' . $product->image)}}" alt="" width="80"
        height="80">
    <div class="media-body">
        <h5 class="mt-0">{{$product->name}}</h5>
    </div>
</div>

<div>
    <label for="status" class="font-weight-bold">Size:</label> <br>
    <input type="text" id="product_id" value="{{$product->id}}" hidden>
    @if ($product->price_L == null)
    <fieldset id="foobar">
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input input-checked" id="{{$product->price . $product->id . ''}}"
                name="price" value="{{$product->price}}">
            <label class="custom-control-label" for="{{$product->price . $product->id . ''}}"><span
                    class="badge badge-warning">{{number_format($product->price) . ' VNĐ'}}</span></label>
        </div>
    </fieldset>
    @else
    <fieldset id="foobar">
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
    <div class="modal-footer" style="position: relative">
        <div class="quantity buttons_added" style="position: absolute; left: 5">
            <input type="button" value="-" class="minus style rounded"><input type="number"
                style="width: 25%; border: 0px solid #f6c23e; text-align: center" step="1" min="1" max=""
                name="quantity" id="quantity" value="1" title="Qty" class="input-text qty text rounded" size="4"
                pattern="" inputmode=""><input type="button" value="+" class="plus style rounded">
        </div>
        <input type="button" readonly class="btn btn-warning" name="total_price" id="total_price" value=""
            style="border: none;position: absolute; right: 5; width: 30%"><span>&nbsp;</span>
    </div>
    <form id="frmAddProduct" action="{{route('cart.add')}}" method="post">
        @csrf
        <input type="text" name="p_quantity" hidden id="p_quantity" value="">
        <input type="text" name="p_price" hidden id="p_price" value="">
        <input type="text" name="p_name" hidden value="{{$product->name}}">
        <input type="text" name="p_id" id="p_id" hidden value="">


    </form>
</div>
@endsection()
@section('script')
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
    $('#p_quantity').val($('#quantity').val());
    var id = $('#product_id').val();
    var price =  $("input[name='price']:checked").val();
    var p_id = id + 'k' + price;
        $('#p_id').val(p_id);
        console.log(p_id);
       if($(this).val() === ''){
           alert('vui lòng chọn món!');
       }else{
        $('#frmAddProduct').submit();
       }

   });

})
</script>
@endsection
