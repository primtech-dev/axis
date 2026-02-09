import{$ as t}from"./jquery-DxCMfk7S.js";import{T as i}from"./tom-select.complete-IxveCKeG.js";import"./_commonjsHelpers-D6-XlEtG.js";import"./jquery-BQXThELV.js";let d=0;function p(){t("#paymentType").val()==="CASH"?(t("#cashAccountWrapper").removeClass("d-none"),t("#dueDateWrapper").addClass("d-none"),t("#dueDate").val("")):(t("#cashAccountWrapper").addClass("d-none"),t("#cashAccountSelect").val(""),t("#dueDateWrapper").removeClass("d-none"))}function n(e){return e.toString().replace(/\D/g,"").replace(/\B(?=(\d{3})+(?!\d))/g,".")}function l(e){return parseFloat(e.replace(/\./g,""))||0}new i("#supplierSelect",{dropdownParent:"body",placeholder:"Cari supplier..."});new i("#cashAccountSelect",{dropdownParent:"body",placeholder:"Pilih sumber dana..."});function y(e){new i(e,{dropdownParent:"body",placeholder:"Cari produk..."})}function b(e){return`
    <tr>
        <td>
            <select name="items[${e}][product_id]"
                    class="form-select product-select">
                <option value="">— Pilih Produk —</option>
                ${window.products.map(o=>`<option value="${o.id}">${o.name}</option>`).join("")}
            </select>
        </td>

        <td>
            <input type="number" step="0.0001"
                   name="items[${e}][qty]"
                   class="form-control qty" value="1">
        </td>

        <td>
            <input type="text"
                   class="form-control price-display"
                   value="0">
            <input type="hidden"
                   name="items[${e}][price]"
                   class="price-raw" value="0">
        </td>

        <td>
            <input type="text"
                   class="form-control subtotal-display"
                   readonly>
            <input type="hidden"
                   name="items[${e}][subtotal]"
                   class="subtotal-raw" value="0">
        </td>

        <td class="text-center">
            <button type="button"
                    class="btn btn-sm btn-outline-danger removeRow">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    </tr>`}function u(){let e=0;t("#itemsTable tbody tr").each(function(){const m=parseFloat(t(this).find(".qty").val())||0,c=l(t(this).find(".price-display").val());t(this).find(".price-raw").val(c);const a=m*c;t(this).find(".subtotal-raw").val(a.toFixed(2)),t(this).find(".subtotal-display").val(n(a.toFixed(0))),e+=a}),t("#subtotal").val(e.toFixed(2)),t("#subtotal_display").val(n(e.toFixed(0)));const o=l(t("#discount_display").val()),s=l(t("#tax_display").val());t("#discount").val(o),t("#tax").val(s);const r=e-o+s;t("#grand_total").val(r.toFixed(2)),t("#grand_total_display").val(n(r.toFixed(0)))}t(function(){t("#paymentType").on("change",p),p(),t("#addRow").on("click",function(){t("#itemsTable tbody").append(b(d));const e=document.querySelector("#itemsTable tbody tr:last-child .product-select");y(e),d++}),t(document).on("input",".qty, .price-display, #discount_display, #tax_display",u),t(document).on("click",".removeRow",function(){t(this).closest("tr").remove(),u()}),t("#addRow").trigger("click")});
