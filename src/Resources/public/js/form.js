const Form = (frm) => {
    if (!frm) return;

    const submitLiqpay = datas => {
        let action = frm.dataset.related ? frm.dataset.related : frm;
        let orderID = action.querySelector('input[name=OrderID]');
        let totalAmount = action.querySelector('input[name=TotalAmount]');
        let inpSignature = action.querySelector('input[name=Signature]');

        if (inpSignature) {
            console.log(datas);
            orderID.value = datas.OrderID;
            totalAmount.value = datas.TotalAmount;
            inpSignature.value = datas.Signature;
            action.action = action.dataset.action;
            action.submit();
        } else {
            alert('Error! Try again later');
        }
    };

    const submitForm = () => {
        let formdata = new FormData();
        frm.querySelectorAll('input[type=hidden]').forEach(inp => formdata.set(inp.name, inp.value));

        fetch(frm.action, {
            method: 'POST',
            body: formdata,
        })
            .then(resp => resp.json())
            .then(answ => {
                if (answ.OrderID && answ.Signature) {
                    submitLiqpay(answ);
                } else {
                    alert('Error! Try again later');
                }
            });

    };
    // if(frm.dataset.)
    if (undefined === frm.dataset.startrun) {
        frm.addEventListener('submit', evt => {
            evt.preventDefault();
            submitForm();
        }, {once: true});
        frm.addEventListener('custom_submit', evt => {
            evt.preventDefault();
            submitForm();
        }, {once: true});
    } else {
        submitForm();
    }
};

export default Form;