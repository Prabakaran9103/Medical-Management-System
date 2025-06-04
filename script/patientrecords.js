function validateForm(){
    if(idValidate() && heightValidate() && weightValidate() &&
        bmiValidate() && sugarLevelValidate()){
            return true;
        }
        else{
            return false;
        }
}


function check(id, regex, error){
    let value = document.getElementById(id).value;
    let err = document.getElementById(error);
    if(regex.test(value)){
        err.style.display = "none";
        return true;
    }
    else{
        err.style.display = "contents";
        return false;
    }
}

function idValidate(){
    let regex = /^[0-9]+$/;

    return check('patient_id', regex, 'iderror');
}

function heightValidate(){
    let regex = /^[0-9]{1,3}[.]?[0-9]{1,2}$/;
    
    return check('height', regex, 'heighterror');
}

function weightValidate(){
    let regex = /^[0-9]{1,3}[.]?[0-9]{1,2}$/;
    return check('weight', regex, 'weighterror');
}

function bmiValidate(){
    if( heightValidate() && weightValidate()){
        let height = document.getElementById("height").value;
        let weight = document.getElementById("weight").value;

        let bmi_value = weight / ((height/100) * (height/100));

        let bmi = document.getElementById("bmi").value= bmi_value.toFixed(2);
        
        return true;
    }
    else{
        return false;
    }
}

function sugarLevelValidate(){
    let regex = /^[0-9]{2,4}$/;
    return check('sugar_level', regex, 'sugarlevelerror');
    
}