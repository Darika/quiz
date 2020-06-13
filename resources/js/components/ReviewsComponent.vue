<template>
    <div class="container">
        <div v-if="reviews.length > 0">
            <div class="reviews-wrap" v-for="rev in this.reviews">
                <nav class="navbar navbar-light bg-light">
                    {{rev.user.name}}
                </nav>
                {{rev.review}}
                <nav class="navbar navbar-light bg-light justify-content-between">
                    {{rev.created_at}}
                    <div>
                        <span class="dislike">{{rev.dislike}}</span>
                        <button class="dislike" v-on:click="rating(rev.id, 'dislike')">
                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                        </button>
                        <span class="like">{{rev.like}}</span>
                        <button class="like" v-on:click="rating(rev.id, 'like')">
                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                        </button>
                    </div>
                </nav>
            </div>
            <div class="pagination">
                <button class="btn btn-default" @click="fetchStories(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Назад
                </button>

                <span style="padding: 0.375rem 0.75rem; font-size: 0.7rem;">Страница {{pagination.current_page}} из {{pagination.last_page}}</span>

                <button class="btn btn-default" @click="fetchStories(pagination.next_page_url)"
                        :disabled="!pagination.next_page_url">
                    Вперед
                </button>
            </div>
        </div>
        <span v-else>
            Здесь пока нет отзывов. Станьте первым!
        </span>
        <div class="form-group">
            <label>Напишите отзыв</label>
            <form name="reviewForm" style="text-align: center;">
                <p v-if="errors.length" class="alert alert-danger">
                    <b>Пожалуйста исправьте указанные ошибки:</b>
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </p>
                <p><textarea class="form-control" v-model="review" name="review"></textarea></p>
                <p><button type="button" v-on:click="sendReview()" class="btn-success">Отправить</button></p>
            </form>
        </div>
    </div>
</template>

<script>
	export default {
		name: "ReviewsComponent",
		props: {
			dataCsrf: String
		},
		data: function () {
			return {
				pagination: {},
				review: '',
				reviews: [],
				uploadFormData: {
					"_token": this.dataCsrf,
				},
                errors: []
			}
		},
        methods: {
			sendReview(e) {
				this.errors = [];

				if (!this.review) {
					this.errors.push('Поле отзыва не должно быть пустым');
				}

				if (!this.errors.length) {

					axios.post('/review/store', {
						'review': this.review
					})
						.then(res => {
							this.reviews.push(res.data.review);
							this.review = '';
						})
						.catch(error => {
							console.error(error);
						});
				}

			},
			makePagination: function(data){
				let pagination = {
					current_page: data.current_page,
					last_page: data.last_page,
					next_page_url: data.next_page_url,
					prev_page_url: data.prev_page_url
				};
				this.pagination = pagination;
			},
			fetchStories: function (page_url) {
				page_url = page_url || '/reviews';
				axios
					.get(page_url)
					.then(response => {
						this.reviews = response.data.data;
						console.log(response.data);
						this.makePagination(response.data);
					});
			},
            rating: function (id, type) {
				var vm = this;
				axios.post('/review/rating/' + id + '/' + type, {
					data: this.data,
					_method: 'patch'
				}).then(function (response) {
					vm.reviews.filter(function(elem){
						if(elem.id == id) {
							elem[type] = response.data.rating;
                        }

                    });
                }).catch(function (error) {
                    console.log(error);
                });
			},
        },
        created() {
			this.fetchStories();
		}
	}
</script>

<style scoped>
    .reviews-wrap {
        list-style: none;
        width: 100%;
        margin: 0 auto;
        border: 1px solid #ccc;
        text-align: left;
        padding: 10px;
        margin-bottom: 10px;
    }
    button.like{
        width: 30px;
        height: 30px;
        margin: 0 auto;
        line-heigth: 50px;
        border-radius: 50%;
        color: rgba(0,150,136 ,1);
        background-color:rgba(38,166,154 ,0.3);
        border-color: rgba(0,150,136 ,1);
        border-width: 1px;
        font-size: 15px;
    }

    button.dislike{
        width: 30px;
        height: 30px;
        margin: 0 auto;
        line-heigth: 50px;
        border-radius: 50%;
        color: rgba(255,82,82 ,1);
        background-color: rgba(255,138,128 ,0.3);
        border-color: rgba(255,82,82 ,1);
        border-width: 1px;
        font-size: 15px;
    }
    span.like{
        color: green;
    }
    span.dislike{
        color:red;
    }
</style>