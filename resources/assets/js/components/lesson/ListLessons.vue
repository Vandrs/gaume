<script>
	import { LessonProvider } from '../../providers/lessonProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { LessonStatus } from '../../components/lesson/LessonStatus';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data () {
			return {
				lessons: [],
				user: window.Laravel.user,
				roles: {
					admin: "ADMIN",
					teacher: "TEACHER",
					student: "STUDENT"
				},
				viewLessonLink: window.Laravel.baseUrl + '/app/aula/',
				status: LessonStatus,
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				}
			};
		},
		mounted() {
			this.getLessons();
		},
		methods: {
			getLessons: function (params)	{
				LessonProvider.list(params)
							  .then((response) => {
							  	this.lessons = response.data.data;
							  	this.pagination.currentPage = response.data.meta.pagination.current_page;
							  	this.pagination.totalPages = response.data.meta.pagination.total_pages;
							  })
							  .catch((error) => {
							  	console.log(error.response);
							  });
			},
			getLessonDuration: function (lesson) {
				var totalHours = 0;
				var acceptedStatus = [this.status.IN_PROGRESS, this.status.FINISHED];
				for (var period of lesson.periods) {
					if (acceptedStatus.indexOf(period.status) >= 0) {
						totalHours += period.hours;
					}
				}
				return totalHours;
			},
			paginate: function (page) {
				this.getLessons({"page":page});
			},
			seePage: function (page) {
				window.location.href = this.viewLessonLink+page;
			}
		}
	}
</script>
<template>
<div>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-bordered table-stripped table-blue-header">
				<thead>
					<tr>
						<th v-if="user.role != roles.student">{{$t('app.student')}}</th>
						<th v-if="user.role != roles.teacher">{{$t('app.teacher')}}</th>
						<th>{{$t('lesson.table.status')}}</th>
						<th>{{$t('lesson.labels.duration')}}</th>
						<th>{{$t('app.actions')}}</th>
					</tr>
				</thead>
				<tbody v-if="lessons.length > 0">
					<tr v-for="lesson in lessons">
						<td v-if="user.role != roles.student">{{lesson.student.name}}</td>
						<td v-if="user.role != roles.teacher">{{lesson.teacher.name}}</td>
						<td>{{$t('lesson.status.'+lesson.status)}}</td>
						<td>{{getLessonDuration(lesson)+" "+$tc('app.hour',getLessonDuration(lesson))}}</td>
						<td>
							<a class="btn btn-default" v-on:click="seePage(lesson.id)"><i class="glyphicon glyphicon-eye-open"></i></a>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="5">{{$t('app.noRegisterFound')}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-xs-12 text-center">
			<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
		</div>
	</div>		
</div>
</template>