<template>
    <fieldset class="rating scale-110">
        <template v-for="star in stars" :key="star.id">
            <input
                type="radio"
                :id="star.id"
                name="rating"
                :value="star.value"
                v-model="rating"
                @change="saveRating"
            />
            <label :class="star.class" :for="star.id"></label>
        </template>
    </fieldset>
</template>

<script setup>
import {ref, onMounted} from 'vue'

const rating = ref(null)

const saveRating = () => {

}

onMounted(() => {

})

const stars = [
    {id: 'star5', value: '10', class: 'full'},
    {id: 'star4half', value: '9', class: 'half'},
    {id: 'star4', value: '8', class: 'full'},
    {id: 'star3half', value: '7', class: 'half'},
    {id: 'star3', value: '6', class: 'full'},
    {id: 'star2half', value: '5', class: 'half'},
    {id: 'star2', value: '4', class: 'full'},
    {id: 'star1half', value: '3', class: 'half'},
    {id: 'star1', value: '2', class: 'full'},
    {id: 'starhalf', value: '1', class: 'half'},
]
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
