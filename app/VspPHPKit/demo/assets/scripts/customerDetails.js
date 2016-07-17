
// run when checkbox is clicked to synchronise the delivery details with billing details
function IsDeliverySame_clicked(calledByCode) {

    if (document.customerform.isDeliverySame.checked) {

        document.customerform.deliveryFirstnames.value = "";
        document.customerform.deliveryFirstnames.className = "inputBoxDisable";
        document.customerform.deliveryFirstnames.disabled = true;
        
        document.customerform.deliverySurname.value = "";
        document.customerform.deliverySurname.className = "inputBoxDisable";
        document.customerform.deliverySurname.disabled = true;

        document.customerform.deliveryAddress1.value = "";
        document.customerform.deliveryAddress1.className = "inputBoxDisable";
        document.customerform.deliveryAddress1.disabled = true;

        document.customerform.deliveryAddress2.value = "";
        document.customerform.deliveryAddress2.className = "inputBoxDisable";
        document.customerform.deliveryAddress2.disabled = true; 

        document.customerform.deliveryCity.value = "";
        document.customerform.deliveryCity.className = "inputBoxDisable";
        document.customerform.deliveryCity.disabled = true;

        document.customerform.deliveryPostCode.value = "";
        document.customerform.deliveryPostCode.className = "inputBoxDisable";
        document.customerform.deliveryPostCode.disabled = true;

        document.customerform.deliveryCountry.value = "";
        document.customerform.deliveryCountry.className = "inputBoxDisable";
        document.customerform.deliveryCountry.disabled = true;

        document.customerform.deliveryState.value = "";
        document.customerform.deliveryState.className = "inputBoxDisable";
        document.customerform.deliveryState.disabled = true;

        document.customerform.deliveryPhone.value = "";
        document.customerform.deliveryPhone.className = "inputBoxDisable";
        document.customerform.deliveryPhone.disabled = true;
    } 
    else 
    {
        document.customerform.deliveryFirstnames.disabled = false;
        document.customerform.deliveryFirstnames.className = "inputBoxEnable";
        document.customerform.deliverySurname.disabled = false;
        document.customerform.deliverySurname.className = "inputBoxEnable";
        document.customerform.deliveryAddress1.disabled = false;
        document.customerform.deliveryAddress1.className = "inputBoxEnable";
        document.customerform.deliveryAddress2.disabled = false;
        document.customerform.deliveryAddress2.className = "inputBoxEnable";
        document.customerform.deliveryCity.disabled = false;
        document.customerform.deliveryCity.className = "inputBoxEnable";
        document.customerform.deliveryPostCode.disabled = false;
        document.customerform.deliveryPostCode.className = "inputBoxEnable";
        document.customerform.deliveryCountry.disabled = false;
        document.customerform.deliveryCountry.className = "inputBoxEnable";
        document.customerform.deliveryState.disabled = false;
        document.customerform.deliveryState.className = "inputBoxEnable";
        document.customerform.deliveryPhone.disabled = false;
        document.customerform.deliveryPhone.className = "inputBoxEnable";
        if(calledByCode!=true) {
            document.customerform.deliveryFirstnames.focus();
        }
    }
}

