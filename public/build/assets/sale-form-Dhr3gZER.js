import{$ as t}from"./jquery-DxCMfk7S.js";import{T as u}from"./tom-select.complete-IxveCKeG.js";import"./_commonjsHelpers-D6-XlEtG.js";import"./jquery-BQXThELV.js";let h=0,n=[];function v(){const e=t("#paymentType").val();t("#cashAccountWrapper").toggleClass("d-none",e==="CREDIT"),t("#dueDateWrapper").toggleClass("d-none",e==="CASH")}function p(){let e=0;t(".item-row").each(function(){const o=parseFloat(t(this).find(".qty").val())||0,s=r(t(this).find(".price-display").val()),l=o*s;t(this).find(".price-raw").val(s),t(this).find(".subtotal-raw").val(l.toFixed(2)),t(this).find(".subtotal-display").val(d(Math.round(l))),e+=l}),t("#subtotal").val(e.toFixed(2)),t("#subtotal_display").val(d(Math.round(e)));const a=r(t("#discount_display").val());t("#discount").val(a);const i=r(t("#tax_display").val());t("#tax").val(i);const c=e-a+i;t("#grand_total").val(c.toFixed(2)),t("#grand_total_display").val(d(Math.round(c)))}function b(e){return`
<div class="border rounded p-3 mb-3 purchase-block" data-index="${e}">
    <div class="row mb-3 align-items-end">
        <div class="col-md-4">
            <label>No Pembelian</label>
            <select class="form-select purchase-select">
                <option value="">— Pilih Pembelian —</option>
                ${window.availablePurchases.map(a=>`<option value="${a.id}">${a.invoice_number}</option>`).join("")}
            </select>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger removePurchase">
                Hapus
            </button>
        </div>
    </div>

    <div class="item-container"></div>
</div>`}function f(e,a,i){a.html(`
        <div class="mb-2">
            <small class="text-muted">Detail item dari pembelian</small>
        </div>

        <div class="row fw-semibold border-bottom pb-2 mb-2 text-muted">
            <div class="col-md-4">Nama Item</div>
            <div class="col-md-2 text-center">Qty</div>
            <div class="col-md-2 text-center">Satuan</div>
            <div class="col-md-2 text-end">Harga Satuan</div>
            <div class="col-md-2 text-end">Subtotal</div>
        </div>
    `),t.get(`/api/purchases/${e}/items`,function(c){let o="";c.forEach((s,l)=>{var m;o+=`
            <div class="row align-items-center g-2 mb-2 item-row">

                <!-- NAMA ITEM -->
                <div class="col-md-4">
                    <div class="fw-medium">${s.product.name}</div>
                    <small class="text-muted">Dari pembelian</small>
                </div>

                <!-- QTY -->
                <div class="col-md-2">
                    <input type="number"
                        step="0.0001"
                        name="items[${i}_${l}][qty]"
                        class="form-control qty text-center"
                        value="${parseFloat(s.qty)}">
                </div>

                <!-- SATUAN -->
                <div class="col-md-2 text-center">
                    <span class="badge bg-light text-dark">
                        ${((m=s.product.unit)==null?void 0:m.name)??"-"}
                    </span>
                </div>

                <!-- HARGA SATUAN -->
                <div class="col-md-2">
                    <input type="text"
                        class="form-control price-display text-end"
                        value="0">

                    <input type="hidden"
                        name="items[${i}_${l}][price]"
                        class="price-raw"
                        value="0">
                </div>

                <!-- SUBTOTAL -->
                <div class="col-md-2">
                    <input type="text"
                        class="form-control subtotal-display text-end"
                        readonly>

                    <input type="hidden"
                        name="items[${i}_${l}][subtotal]"
                        class="subtotal-raw"
                        value="0">
                </div>

                <!-- HIDDEN -->
                <input type="hidden"
                       name="items[${i}_${l}][purchase_item_id]"
                       value="${s.id}">
                <input type="hidden"
                       name="items[${i}_${l}][product_id]"
                       value="${s.product_id}">
            </div>
            `}),a.append(o),p()}),console.log("Purchase item qty:",it.qty)}t(function(){new u("#customerSelect",{dropdownParent:"body"}),new u("#cashAccountSelect",{dropdownParent:"body"}),t("#paymentType").on("change",v),v(),t("#addPurchase").on("click",function(){const e=h++;t("#purchaseContainer").append(b(e));const i=t(`.purchase-block[data-index="${e}"]`).find(".purchase-select")[0];new u(i,{dropdownParent:"body",searchField:["text"],placeholder:"Cari nomor pembelian"})}),t(document).on("change",".purchase-select",function(){const e=t(this),a=e.val(),i=e.closest(".purchase-block"),c=i.data("index"),o=i.find(".item-container");if(!a)return;if(n.includes(a)){alert(`Nomor pembelian ${e.find("option:selected").text()} sudah dipilih`),e.val("").trigger("change");return}const s=i.data("purchase-id");s&&(n=n.filter(l=>l!==s)),i.data("purchase-id",a),n.push(a),o.empty(),f(a,o,c)}),t(document).on("input",".price-display, #discount_display, #tax_display",function(){const e=r(t(this).val());t(this).val(d(e)),p()}),t(document).on("click",".removePurchase",function(){const e=t(this).closest(".purchase-block"),a=e.data("purchase-id");a&&(n=n.filter(i=>i!==a)),e.remove(),p()})});function d(e){return e.toString().replace(/\D/g,"").replace(/\B(?=(\d{3})+(?!\d))/g,".")}function r(e){return parseFloat(e.replace(/\./g,""))||0}
