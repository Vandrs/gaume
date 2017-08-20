<div class="row margin-top-20">
	<div class="col-xs-12">
		<div class='discus-container'>
			<div id="disqus_thread" class="disqus-container-style"></div>
		</div>
	</div>
</div>
<script>
	var disqus_config = function () {
		this.page.url = "{{$page_url}}";  
		this.page.identifier = "{{$page_id}}";
	};	
	(function() {
	var d = document, s = d.createElement('script');
	s.src = 'https://monzy.disqus.com/embed.js';
	s.setAttribute('data-timestamp', +new Date());
	(d.head || d.body).appendChild(s);
	})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>