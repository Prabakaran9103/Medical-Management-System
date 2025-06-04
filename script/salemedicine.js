function addItems(){
    let table = document.getElementById('cart');

    let newrow = table.insertRow(-1);

    let col0 = newrow.insertCell(0);
    let col1 = newrow.insertCell(1);
    let col2 = newrow.insertCell(2);
    let col3 = newrow.insertCell(3);
    let col4 = newrow.insertCell(4);
    let col5 = newrow.insertCell(5);

    col0.innerHTML = "1";
    col1.innerHTML = "1";
    col2.innerHTML = "1";
    col3.innerHTML = "1";
    col4.innerHTML = "1";
    col5.innerHTML = "1";


    
}