<template>

    <PrimaryButton class="h-7 text-xs border border-zinc-700 flex gap-1.5"
                    @click.prevent="handlePin">

        <svg xmlns="http://www.w3.org/2000/svg" 
            width="18" 
            height="18" 
            viewBox="0 0 24 24"
        >
            <path fill="#9198a1" 
                fill-rule="evenodd" 
                d="m16.475 4.375l3.172 3.176c1.008 1.008 1.824 1.825 2.35 2.535c.541.73.891 1.5.701 2.377c-.19.879-.826 1.434-1.62 1.875c-.773.429-1.854.835-3.187 1.336l-1.977.743c-.795.298-1.011.391-1.172.53q-.12.106-.216.237c-.124.173-.197.397-.422 1.216l-.013.045c-.228.831-.417 1.517-.624 2.032c-.21.523-.493 1.018-1.002 1.309a2.34 2.34 0 0 1-1.16.307c-.587 0-1.078-.292-1.519-.642c-.434-.346-.936-.85-1.545-1.46l-1.588-1.588l-4.122 4.127a.75.75 0 0 1-1.062-1.06l4.124-4.128l-1.535-1.537C3.453 15.2 2.954 14.7 2.61 14.268c-.349-.438-.638-.926-.642-1.508a2.34 2.34 0 0 1 .313-1.182c.29-.505.782-.786 1.302-.995c.512-.205 1.193-.393 2.018-.62l.045-.013c.82-.226 1.045-.3 1.217-.424q.135-.097.242-.222c.138-.163.23-.38.523-1.18l.716-1.956c.495-1.349.895-2.442 1.32-3.222c.437-.803.99-1.448 1.872-1.642c.882-.195 1.655.158 2.389.702c.712.53 1.535 1.353 2.55 2.369M13.03 3.21c-.602-.448-.921-.498-1.171-.443s-.519.235-.878.895c-.365.67-.729 1.658-1.25 3.081L9.036 8.64l-.04.108c-.233.64-.414 1.136-.75 1.529q-.224.264-.506.467c-.42.302-.927.441-1.585.622l-.11.03c-.882.243-1.48.41-1.903.58c-.425.17-.527.29-.562.35a.84.84 0 0 0-.112.424c0 .07.03.225.316.584c.284.357.722.797 1.368 1.444l4.117 4.12c.65.652 1.093 1.093 1.452 1.38c.36.286.516.315.585.315a.83.83 0 0 0 .416-.11c.06-.034.181-.136.353-.564s.338-1.03.582-1.917l.03-.11c.18-.657.32-1.164.62-1.583q.197-.274.453-.496c.39-.337.882-.522 1.519-.76l.107-.04l1.917-.72c1.408-.53 2.383-.898 3.046-1.266c.651-.361.829-.63.883-.88c.054-.251.003-.57-.44-1.168c-.452-.61-1.187-1.349-2.251-2.413L15.459 5.48c-1.071-1.072-1.816-1.814-2.429-2.27" 
                clip-rule="evenodd"
            />
        </svg>
        
        <p ref="pinButtonText">Pin</p>
    </PrimaryButton>

    <ButtonWithDropdown>
        <div class="flex gap-2 h-full py-1 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                width="18" 
                height="18" 
                viewBox="0 0 24 24"
            >
                <path fill="#9198a1" 
                    d="M12 3a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-1 5.874A4.002 4.002 0 0 1 12 1a4 4 0 0 1 1 7.874V11h4a3 3 0 0 1 3 3v1.126A4.002 4.002 0 0 1 19 23a4 4 0 0 1-1-7.874V14a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v1.126A4.002 4.002 0 0 1 5 23a4 4 0 0 1-1-7.874V14a3 3 0 0 1 3-3h4zM19.003 17h-.006a2 2 0 1 0 .006 0M5 17a2 2 0 1 0 0 4a2 2 0 0 0 0-4"
                />
            </svg>
            <p class="items-center">Fork</p>
            <span class="bg-[#2f3742] p-[7px] h-full flex items-center rounded-full">
                0 <!--  NUMBER TO CHANGE LATER!  --> 
            </span>
        </div>
        
    </ButtonWithDropdown> 

    <ButtonWithDropdown @click.prevent="handleStar">
        <div class="flex gap-1.5 h-full py-1 items-center">
            <svg v-if="!starStatus"
                xmlns="http://www.w3.org/2000/svg" 
                width="18" 
                height="18" 
                viewBox="0 0 24 24"
                fill="yellow"
            >
                <path fill="#9198a1" 
                    d="m8.85 16.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425zm3.15.45l-4.15 2.5q-.275.175-.575.15t-.525-.2t-.35-.437t-.05-.588l1.1-4.725L3.775 10.8q-.25-.225-.312-.513t.037-.562t.3-.45t.55-.225l4.85-.425l1.875-4.45q.125-.3.388-.45t.537-.15t.537.15t.388.45l1.875 4.45l4.85.425q.35.05.55.225t.3.45t.038.563t-.313.512l-3.675 3.175l1.1 4.725q.075.325-.05.588t-.35.437t-.525.2t-.575-.15zm0-5.025"
                />
            </svg>

            <!-- YELLOW STAR -->
            <svg v-if="starStatus"
                xmlns="http://www.w3.org/2000/svg" 
                width="18" 
                height="18" 
                viewBox="0 0 24 24"
            >
                <path fill="yellow" 
                    d="m12 17.275l-4.15 2.5q-.275.175-.575.15t-.525-.2t-.35-.437t-.05-.588l1.1-4.725L3.775 10.8q-.25-.225-.312-.513t.037-.562t.3-.45t.55-.225l4.85-.425l1.875-4.45q.125-.3.388-.45t.537-.15t.537.15t.388.45l1.875 4.45l4.85.425q.35.05.55.225t.3.45t.038.563t-.313.512l-3.675 3.175l1.1 4.725q.075.325-.05.588t-.35.437t-.525.2t-.575-.15z"
                    />
            </svg>

            <!-- <p v-if="star">Starred</p>
            <p v-if="!star"></p> -->
            <p ref="starButtonText"> Star </p>
            <span class="bg-[#2f3742] p-[7px] h-full flex items-center rounded-full"
                    ref="starAmount">
                {{ total_stars }} <!--  NUMBER TO CHANGE LATER!  --> 
            </span>
        </div>
        
    </ButtonWithDropdown> 

</template>

<script setup>

    import PrimaryButton from './PrimaryButton.vue';
    import ButtonWithDropdown from './ButtonWithDropdown.vue';
    import { defineEmits, ref } from 'vue';
    import { onMounted } from 'vue';

    const props = defineProps({
        total_stars: Object,
        star: Boolean,
        pin: Boolean,
        info: Object,

    })

    const emit = defineEmits();

    const pinStatus = ref(props.pin); // WILL CHANGE FOR LATER WITH BACKEND
    const pinButtonText =  ref(null);

    const starStatus = ref(props.star);
    const starAmount = ref(props.total_stars);
    const starButtonText = ref(null);

    const togglePinText = () => {
         
        if (pinStatus.value) pinButtonText.value.textContent = "Unpin";
        else pinButtonText.value.textContent = "Pin";
    }    
 
    const toggleStarText = () => {
 
        if (starStatus.value) starButtonText.value.textContent = "Starred";
        else starButtonText.value.textContent = "Star";
    }

    const handlePin = async () => {
        
        pinStatus.value = !pinStatus.value;

        togglePinText();
        const response = await fetch(`/pin/${pinStatus.value}/${props.info.user_name}/${props.info.id}`);
        const data = await response.json();

        console.log(data)
    }

    const handleStar = async () => {

        starStatus.value = !starStatus.value;

        if (starStatus.value) starAmount.value.textContent = parseInt(starAmount.value.textContent) + 1;
        else starAmount.value.textContent = parseInt(starAmount.value.textContent) - 1;
        
        toggleStarText()

        // emit('update-star', starStatus.value)
        const response = await fetch(`/star/${starStatus.value}/${props.info.user_name}/${props.info.id}`);
        
    }


    onMounted(() => {
        toggleStarText();
        togglePinText();
    });
    
</script>