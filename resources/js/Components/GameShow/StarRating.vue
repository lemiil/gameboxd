<script setup>
import {defineProps, defineEmits} from 'vue'

const props = defineProps({
    modelValue: Number
})
const emit = defineEmits(['update:modelValue'])

const setRating = (value) => {
    emit('update:modelValue', value)
}
</script>

<template>
    <fieldset class="rate">
        <template v-for="n in 11" :key="n">
            <input
                type="radio"
                :id="'rating' + (10 - n + 1)"
                name="rating"
                :value="10 - n + 1"
                :checked="modelValue === (10 - n + 1)"
                @change="setRating(10 - n + 1)"
            />
            <label
                :for="'rating' + (10 - n + 1)"
                :class="{ half: (10 - n + 1) % 2 !== 0 }"
            ></label>
        </template>
    </fieldset>
</template>


<style scoped>
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

.rate {
    display: inline-block;
    border: 0;
}

.rate > input {
    display: none;
}

.rate > label {
    float: right;
}

.rate > label:before {
    display: inline-block;
    font-size: 1.1rem;
    padding: .3rem .2rem;
    margin: 0;
    cursor: pointer;
    font-family: FontAwesome;
    content: "\f005 ";
}

.rate > label:last-child:before {
    content: "\f006 ";
}

.rate .half:before {
    content: "\f089 ";
    position: absolute;
    padding-right: 0;
}

input:checked ~ label,
label:hover, label:hover ~ label {
    color: #73B100;
}

input:checked + label:hover, input:checked ~ label:hover,
input:checked ~ label:hover ~ label,
label:hover ~ input:checked ~ label {
    color: #A6E72D;
}

</style>
