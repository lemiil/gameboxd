<template>
    <fieldset class="rating scale-110">
        <input type="radio" id="star5" name="rating" value="10"/><label class="full" for="star5"
    ></label>
        <input type="radio" id="star4half" name="rating" value="9"/><label class="half" for="star4half"
    ></label>
        <input type="radio" id="star4" name="rating" value="8"/><label class="full" for="star4"
    ></label>
        <input type="radio" id="star3half" name="rating" value="7 and a half"/><label class="half" for="star3half"
    ></label>
        <input type="radio" id="star3" name="rating" value="6"/><label class="full" for="star3"
    ></label>
        <input type="radio" id="star2half" name="rating" value="5"/><label class="half" for="star2half"
    ></label>
        <input type="radio" id="star2" name="rating" value="4"/><label class="full" for="star2"
    ></label>
        <input type="radio" id="star1half" name="rating" value="3"/><label class="half" for="star1half"
    ></label>
        <input type="radio" id="star1" name="rating" value="2"/><label class="full" for="star1"
    ></label>
        <input type="radio" id="starhalf" name="rating" value="1"/><label class="half" for="starhalf"
    ></label>
    </fieldset>
</template>

<script setup>
</script>


<style scoped>
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

.rating > input {
    display: none;
}

.rating > label:before {
    margin: 5px;
    font-size: 1.25em;
    font-family: FontAwesome;
    display: inline-block;
    content: "\f005";
}

.rating > .half:before {
    content: "\f089";
    position: absolute;
}

.rating > label {
    color: #ddd;
    float: right;
}


.rating > input:checked ~ label,
.rating:not(:checked) > label:hover,
.rating:not(:checked) > label:hover ~ label {
    color: #FFD700;
}


.rating > input:checked + label:hover,
.rating > input:checked ~ label:hover,
.rating > label:hover ~ input:checked ~ label,
.rating > input:checked ~ label:hover ~ label {
    color: #FFED85;
}
</style>
