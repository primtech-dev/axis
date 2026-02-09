import{T as f}from"./tom-select.complete-IxveCKeG.js";import{$ as o}from"./jquery-DxCMfk7S.js";import"./_commonjsHelpers-D6-XlEtG.js";import"./jquery-BQXThELV.js";let b=0;o(function(){document.querySelectorAll(".tom-select").forEach(t=>{new f(t,{create:!1,sortField:{field:"text",direction:"asc"}})})});function i(t){isNaN(t)&&(t=0);const n=t.toString().split(".");return n[0]=n[0].replace(/\B(?=(\d{3})+(?!\d))/g,"."),n.join(",")}function r(t){return t&&parseFloat(t.toString().replace(/\./g,"").replace(",","."))||0}o(function(){const t=o("#itemsTable tbody"),n=o("#subtotal"),d=o("#grandTotal"),u=o("#totalText");function a(){let e=0;t.find("tr").each(function(){const c=o(this),p=r(c.find(".qty").val()),m=r(c.find(".price").val()),l=p*m;e+=l,c.find(".subtotal").val(i(l.toFixed(2)))}),n.val(e),d.val(e),u.text(i(e.toFixed(2)))}function s(){const e=b++;t.append(`
            <tr>
                <td>
                    <input type="text"
                        name="items[${e}][name]"
                        class="form-control"
                        required>
                </td>
                <td>
                    <input type="text"
                        name="items[${e}][qty]"
                        class="form-control qty text-end"
                        value="0">
                </td>
                <td>
                    <input type="text"
                        name="items[${e}][price]"
                        class="form-control price text-end"
                        value="0">
                </td>
                <td>
                    <input type="text"
                        name="items[${e}][subtotal]"
                        class="form-control subtotal text-end"
                        readonly>
                </td>
                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-danger removeRow">×</button>
                </td>
            </tr>
        `),a()}t.on("input",".qty, .price",function(){const e=r(this.value);this.value=i(e),a()}),t.on("click",".removeRow",function(){o(this).closest("tr").remove(),a()}),o("#addRow").on("click",s),o("#expenseForm").on("submit",function(){a(),t.find(".qty, .price, .subtotal").each(function(){this.value=r(this.value)})}),o("#paymentType").on("change",function(){const e=o(this).val();o("#dueDateWrapper").toggleClass("d-none",e!=="CREDIT"),o("#cashAccountWrapper").toggleClass("d-none",e!=="CASH")}).trigger("change"),s()});
