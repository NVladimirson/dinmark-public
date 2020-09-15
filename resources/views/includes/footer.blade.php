<!-- begin #footer -->
<section class="subscribe">
    <h3>@lang('footer.social_block_header')</h3>
    <div class="flex">
        <a href="https://www.facebook.com/DINMARKUA/" target="_blank"><img src="https://dinmark.com.ua/images/facebook.svg" alt="facebook.svg"></a>
        <a href="https://www.youtube.com/channel/UCaW1nX048qzfDJx6qe-5CgA" target="_blank"><img src="https://dinmark.com.ua/images/youtube.svg" alt="youtube.svg"></a>
        <a href="https://t.me/joinchat/AAAAAFVRhL9rVInaUi4Y9Q" target="_blank"><img src="https://dinmark.com.ua/images/telegram.svg" alt="telegram.svg"></a>
        <a href="https://www.instagram.com/dinmark_ua/" target="_blank"><img src="https://dinmark.com.ua/images/instagram.svg" alt="instagram.svg"></a>
        <a href="https://www.pinterest.ch/dinmark_ua/" target="_blank"><img src="https://dinmark.com.ua/images/pinterest.svg" alt="pinterest.svg"></a>
    </div>
</section>
<!--  -->
<footer id="footer">
    <div class="container flex">
        <div class="hideTablet">
            <h4>@lang('footer.my_account_header')</h4>
            <a href="{{route('orders')}}">@lang('footer.my_account_orders')</a>
            <a href="https://novaposhta.ua/tracking" target="_blank">@lang('footer.my_account_np')</a>
            <a href="{{route('catalogs')}}">@lang('footer.my_account_wishlist')</a>
        </div>
        <div class="hideTablet">
            <h4>@lang('footer.cooperation_header')</h4>
            <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/affiliate-program">@lang('footer.cooperation_affiliate_program')</a>
            <a href="{{route('ticket.create')}}">@lang('footer.cooperation_contacts')</a>
            <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/vacancies">@lang('footer.cooperation_vacancies')</a>
        </div>
        <div class="hideTablet">
            <h4>@lang('footer.useful_information_header')</h4>
            <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/shop">@lang('footer.useful_information_catalog')</a>
            <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/technical-tables">@lang('footer.useful_information_technical_tables')</a>
            <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/din-compliance-tables">@lang('footer.useful_information_din_compliance')</a>
        </div>
        <div>
            <h4>@lang('footer.contacts_header')</h4>
            <p>@lang('footer.contacts_address')</p>
            <p><a href="mailto:info@dinmark.com.ua">info@dinmark.com.ua</a></p>
            <p><span class="a_tel">+38 (096) 011-01-03</span></p>
            <p>@lang('footer.contacts_working_time')</p>
        </div>
    </div>
    </footer>
    <div class="copyright">
        © Dinmark {{\Carbon\Carbon::now()->format('Y')}}  <span>@lang('footer.copyright')</span>
    </div>
<!-- end #footer -->
