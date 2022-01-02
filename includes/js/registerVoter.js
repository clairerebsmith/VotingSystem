//              The functions below is to check whether the email ID already exists or not.
//              JS is constant checking whether anything has been entered in either the email field
//              If the user has enetered something, send that data across to emailCheck for verifying whether a email is available
//              or whether it exists in the table

$(document).ready(function(){
    $('#email').on("keyup input", function(){
        var inputVal = $(this).val();
        $.post( "template/emailCheck.php", { email:inputVal }, function (data){
            if(data){
                $("#registerAvailable").css({'color':'red', 'font-weight':'bold'});
                $("#registerAvailable").html(data);
            }else{
                $("#registerAvailable").html(data);
            }
        });
    });
});

// The below function run to make sure that the person who is trying to register is over 18
// and to make sure that the have selected an apprriate response for choosing a gender. If this is not the case, the user cannot go to the next tab

function checkForm(n){
    let filled = true;
    let fields;

    if(n == 0){
        fields = $(".pd-item-required").find("select, input").serializeArray();

        let today = new Date();
        let birthDate = fields[3].value.split("-");

        let year = birthDate[0];
        let month = birthDate[1] - 1;
        let day =birthDate[2];

        let age = today.getFullYear() - year;
        let m = today.getMonth() - month;
        let d = today.getDate() - day;
        if (m < 0 || (m === 0 && d < 0)) {
            --age;
        }
        if(age < 18){
            filled = false;
            $("#DOBOver").css({'color':'red', 'font-weight':'bold'});
            $("#DOBOver").html("Please make sure that you are over 18");
        }

    }else {
        fields = $(".ad-item-required").find("select, input").serializeArray();
    }

    for(let i = 0; i < fields.length; ++i){
        if(fields[i].value === ''){
            filled = false;
        }
    }

    let select = document.getElementById("gender");
    if(!select.value){
        filled = false;
    }
    return filled;
}

let currentTab = 0;
let nextTab;
let lastTab;

showTab(currentTab)

// displays the tab the user should be viewing

function showTab(n){
    let display = document.getElementsByClassName("tab-pane");
    display[n].style.display = "inline";
}

// displays the next tab and closes the previous tab

function nextView(){
    let tab = document.getElementsByClassName("tab-pane");
    nextTab = currentTab + 1;

    if(checkForm(currentTab)){
        tab[currentTab].style.display = "none";
        showTab(nextTab);
        lastTab = currentTab;
        currentTab = nextTab;

    }else{
        showTab(currentTab);

    }
}

// displays the previous tab and closes the current tab

function lastView(){
    let display = document.getElementsByClassName("tab-pane");
    display[currentTab].style.display = "none";
    showTab(lastTab);
    currentTab = lastTab;
    lastTab = lastTab - 1;
}