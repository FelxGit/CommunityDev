<template>
  <footer class="main-footer full mod">
		<div class="sleeve">
			<div class="footer-logo">
				<a href="#">
          <img class="h-12" src="../../../assets/chronostep.png" />
        </a>
				<p>
					We’re organisational culture and identity specialists.
					Our mission is to help companies articulate who they are,
					and why they matter.
                    <br/>
                    <small>©2023 Chronostep Inc.</small>
				</p>
			</div>
			<nav class="nav">
				<div class="column">
					<p class="nav-title">Company</p>
                    <div class="the-nav">
                        <ul id="menu-company" class="">
                            <li class="nav-item menu-item-home">
                                <router-link :to="{ name: 'landing-page'}"><span>Home</span></router-link>
                            </li>
                            <li class="nav-item">
                                <router-link :to="{ name: 'about' }"><span>About</span></router-link>
                            </li>
                            <li class="nav-item">
                                <router-link :to="{ name: 'contact' }"><span>Contact</span></router-link>
                            </li>
                        </ul>
                    </div>
                </div>
				<div class="column">
					<p class="nav-title">Discover</p>
                    <div class="the-nav">
                        <ul id="menu-discover" class="">
                            <li class="nav-item"><a href="#"><span>What is Chronostep Community?</span></a></li>
                            <li class="nav-item current"><a href="#"><span>Your Journey to us</span></a></li>
                            <li class="nav-item"><a href="#"><span>The Articles</span></a></li>
                        </ul>
                    </div>
                </div>
				<div class="column address">
					<p class="nav-title">Address</p>
					<address>
						<p>
							<span>F. Cabahug Street,</span>
							<span>Barangay Kasambangan</span>
							<span>Cebu City</span>
						</p>
					</address>
				</div>
				<div class="column social">
					<p class="nav-title">Social</p>
					<ul>
						<li class="twitter">
							<span><img src="https://beliyf.com/content/themes/beliyf/assets/graphics/social-twitter-dark.svg" alt=""></span>
							<a href="#" target="_blank">Twitter</a>
						</li>
						<li class="facebook">
							<span><img src="https://beliyf.com/content/themes/beliyf/assets/graphics/social-facebook-dark.svg" alt=""></span>
							<a href="#" target="_blank">Facebook</a>
						</li>
						<li class="linkedin">
							<span><img src="https://beliyf.com/content/themes/beliyf/assets/graphics/social-linkedin-dark.svg" alt=""></span>
							<a href="#" target="_blank">LinkedIn</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</footer>
</template>
<style scoped lang="scss">
@import '../../../sass/imports';

.journey .main-footer {
    background: $brand-theme-color-secondary;
}
.main-footer {
    width: 100%;
    padding: $base-gap $base-comp-gap-x;
    background-color: black;
    position: relative;
    z-index: $z-index-level-1;
}
.full {
    width: 100%;
    margin: 0 auto;
}
.journey .main-footer>.sleeve {
    border: 0;
    margin: 0 auto;
    padding: 60px 0;
}
.main-footer .footer-logo {
    display: grid;
    grid-gap: $base-gap;
    color: $brand-mute
}
.main-footer .nav {
    flex: 0 1 720px;
    display: flex;
    flex-wrap: wrap;
}
.main-footer .nav .column {
    margin-right: 12%;
    padding: 20px 0 0;
    -ms-flex: 0 0 auto;
    -webkit-flex: 0 0 auto;
    flex: 0 0 auto;
}
.main-footer .nav .nav-title {
    margin: 0 0 18px;
    color: $brand-mute;
    font-family: "Gotham A","Gotham B";
    font-size: 16px;
    line-height: 24px;
    font-weight: 500;
}
.main-footer .nav li {
    font-size: 13px;
    line-height: 23px;
}
.journey .main-footer .nav li a {
    border-bottom-color: #E2E2E2;
}
.main-footer .nav li a {
    border: 0;
    border-bottom: 1px solid #FAF9FA;
    color: #868686;
    font-family: "Gotham A","Gotham B";
    -moz-transition: -moz-all .2s;
    -o-transition: -o-all .2s;
    -ms-transition: -ms-all .2s;
    -webkit-transition: -webkit-all .2s;
    transition: all .2s;
}
.main-footer .nav .column.social li {
    display: block;
}
.main-footer .nav .column.social li span {
    width: 18px;
    margin-right: 8px;
    display: inline-block;
    text-align: center;
}
.main-footer .nav .column.social .twitter img {
    width: 17px;
    height: 14px;
    margin-bottom: -2px;
}
.main-footer .nav .column.social .facebook img {
    width: 7px;
    height: 16px;
    margin-bottom: -3px;
}
.main-footer .nav .column.social .linkedin img {
    width: 14px;
    height: 13px;
}
.main-footer .nav .column.social li a {
    font-weight: 500;
}
.main-footer .footer-logo img {
    display: block;
    max-width: 124px;
    max-height: 44px;
}
</style>