$('#add-image').click(function(){
    const index = +$('#widgets-counter').val() //to get the index of the created feilds. + to turn expression in integer
    const protoTemplate = $('#ad_images').data('prototype').replace(/__name__/g, index); //to retrieve the entry prototype. regex to replace name with index value.
    $('#ad_images').append(protoTemplate); //adds entry
    $('#widgets-counter').val(index + 1);
    handleDeleteButtons(); 
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    })
}

function uodateWidgetCounter(){
    const count = +$('#ad_image div.form-group').length;
    $('#widgets-counter').val(count);
}

uodateWidgetCounter();
handleDeleteButtons();