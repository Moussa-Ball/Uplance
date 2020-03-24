<template>
    <!-- Form -->
    <form @submit.prevent="storeReview">
        <div v-if="who == 'freelancer'" class="feedback-yes-no">
            <strong>Was this delivered on budget?</strong>
            <div class="radio">
                <input id="radio-1" v-model="onbudget" type="radio" value="yes">
                <label for="radio-1"><span class="radio-label"></span> Yes</label>
            </div>

            <div class="radio">
                <input id="radio-2" v-model="onbudget" value="no" type="radio">
                <label for="radio-2"><span class="radio-label"></span> No</label>
            </div>
        </div>

        <div  v-if="who == 'freelancer'" class="feedback-yes-no">
            <strong>Was this delivered on time?</strong>
            <div class="radio">
                <input id="radio-3" v-model="ontime" value="yes" type="radio">
                <label for="radio-3"><span class="radio-label"></span> Yes</label>
            </div>

            <div class="radio">
                <input id="radio-4" v-model="ontime" value="no" type="radio">
                <label for="radio-4"><span class="radio-label"></span> No</label>
            </div>
        </div>

        <div class="feedback-yes-no">
            <strong>Your Rating</strong>
            <star-rating :star-size="25" :show-rating="false" v-model="rating" :glow="none"/>
            <div class="clearfix"></div>
        </div>
        <textarea class="with-border" placeholder="Comment" v-model="comment" id="message2" cols="7"></textarea>
        <button v-if="button" class="button full-width button-sliding-icon ripple-effect" type="submit">Leave a Review <i class="icon-material-outline-arrow-right-alt"></i></button>
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8"/>
    </form>
</template>

<script>
import { HalfCircleSpinner } from 'epic-spinners'
import { setTimeout } from 'timers';
export default {
    props: ['id', 'who'],
    data () {
        return {
            rating: 0,
            ontime: '',
            comment: '',
            onbudget: '',
            button: true,
            loading: false,
        }
    },
    components: {
        HalfCircleSpinner
    },
    methods: {
        storeReview () {
            let _this = this
            _this.loading = true
            _this.button = false
            axios.post('/api/store-review~' + _this.id, {
                rating: _this.rating,
                ontime: _this.ontime,
                comment: _this.comment,
                onbudget: _this.onbudget,
            }).then(response => {
                _this.loading = false
                new Noty({
                    text: '<strong>' + response.data.message + '</strong>',
                    type: 'success',
                    theme: 'metroui',
                    progressBar: true,
                    timeout: 5000,
                }).show();
                setTimeout(() => {
                    window.location.href = '/profile/reviews'
                }, 2500)
            }).catch(e => {
                let data = error.response.data
                    for (let key in data.errors)
                    {
                        new Noty({
                            text: '<strong>' + data.errors[key][0] + '</strong>',
                            type: 'error',
                            theme: 'metroui',
                            progressBar: true,
                            timeout: 5000,
                        }).show();
                    }
                    _this.button = true
                    _this.loading = false
            })
        }
    }
}
</script>
