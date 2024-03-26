<template>
    <div @dragenter.prevent="toggleActive" @dragleave="toggleActive" @dragover.prevent @drop="toggleActive" class="dropzone" :class="{'active-dropzone': active }">
        <span>Drag or drop file...</span>
        <span>OR</span>
        <label for="dropzoneFile">Select file</label>
        <input type="file" id="dropzoneFile" class="dropzoneFile"/>
        
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: "DropZone",
    
    setup() {
        const active = ref(false);
        const toggleActive = () => {
            active.value = !active.value;
        }

        return { active, toggleActive };
    },
    
}
</script>

<style lang="scss" scoped>
.dropzone {
    width:100%;
    height:200px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 16px;
    border:2px dashed #41b883;
    background-color: #fff;
    transition: .3s ease all;
    
    label {
        padding: 8px 12px;
        color:#fff;
        background-color: #41b883;
        transition: .3s ease all;
    }

    input[type="file"] {
        display:none;
    }
}

.active-dropzone {
    background-color: #41b883;
}


</style>