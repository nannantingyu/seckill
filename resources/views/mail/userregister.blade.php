@component('mail::message')
## {{$username}}, 欢迎注册Yjshare

请点击下面的链接，完成您的注册认证：
@component('mail::button', ['url'=>$url])
验证
@endcomponent

**我们将为您奉上最新最全最及时的资讯，最好用的工具，如有任何疑问，请咨询nannantingyu@gmail.com**

谢谢 <br>
以上

@endcomponent