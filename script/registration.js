
function formValidate(){
    if(nameValidate() && contactValidate() && emailValidate()
    && addressValidate() && pswValidate() && rpswValidate()){
        return true;
    }
    else{
        alert("Fill All Fields with valid input");
        return false;
    }
}



function check(id, error, regex){
    const val = document.getElementById(id).value;
    const err = document.getElementById(error);
    if(regex.test(val)){
        err.style.display = 'none';
        return true;
    }
    else{
        err.style.display = 'block';
        return false;
    }
}
function nameValidate(){
    let regex = /^[a-zA-Z .]{3,20}$/;
    return check('name', 'nameerror', regex);
}

function contactValidate(){
    let regex = /^[0-9]{10}$/;
    return check('contact_no', 'contacterror', regex);
}

function emailValidate(){
    let regex = /^[a-zA-Z0-9_]{1,30}@[a-z]{1,8}(.com|.in|.org|.edu]){1}$/;
    return check('email', 'emailerror', regex);
}

function addressValidate(){
    let regex = /^.{10,150}$/;
    return check('address', 'addresserror', regex);
}

function pswValidate(){
    let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{8,15}$/;
    return check('psw', 'pswerror', regex);
}

function rpswValidate(){
    let error = document.getElementById('rpswerror');
    if(document.getElementById('psw').value == document.getElementById('rpsw').value){
        error.style.display = 'none';
        return true;
    }
    else{
        error.style.display = 'block';
        return false;
    }
}