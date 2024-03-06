@extends('layouts.app')

@section('content')
<div class="team-container">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-items">
        <div class="faq-item">
            <h3 class="sans-serif">How do I view the top news feed?</h3>
            <p class="faq-answer">You can view the top news feed by accessing the homepage, clicking the website's
                title. It showcases some of the top
                news on our
                website, allowing you to quickly catch up with the latest and most popular tech news.</p>
        </div>
        <div class="faq-item">
            <h3 class="sans-serif">How can I keep up with recent news?</h3>
            <p class="faq-answer">Our recent news feed feature allows you to stay updated with the latest events. You
                can find this option
                alongside the top news feed on the homepage.</p>
        </div>
        <div class="faq-item">
            <h3 class="sans-serif">Can I search for specific news items?</h3>
            <p class="faq-answer">Yes, our website offers both exact match and full-text search capabilities. You can
                use these features to
                find the most precise content for your search, whether it's a specific phrase or a broader topic.</p>
        </div>
        <div class="faq-item">
            <h3 class="sans-serif">What can I do as an authenticated user?</h3>
            <p class="faq-answer">As an authenticated user, you have access to personalized features like creating news
                items, editing your
                profile, viewing and following other users, and much more. Creating an account offers unique
                capabilities to
                enhance your experience on our platform.</p>
        </div>
        <div class="faq-item">
            <h3>How can I create a news article?</h3>
            <p class="faq-answer">After you log in, on the main page, click on the button titled "Write
                article". Then, insert an appropriate title, upload an image you think relates to your content, select
                the appropriate topics and write the body of your news article. You can then edit them whenever you
                want, or delete them as long as it doesn't have any upvotes or comments yet.</p>
        </div>
        <div class="faq-item">
            <h3>How do I edit my news article?</h3>
            <p class="faq-answer">To edit your news article, navigate to the article you want to edit and click on the
                'Edit' button. Make your desired changes and save them. </p>
        </div>
        <div class="faq-item">
            <h3>Can I follow specific topics on the platform?</h3>
            <p class="faq-answer">Yes, you can follow specific topics to personalize your news feed. Simply navigate to
                the topic you're interested in, or hover over it in a specific news article, and click on the 'Follow'
                button. This will ensure that news related to
                these topics appears more prominently in your feed.</p>
        </div>
        <div class="faq-item">
            <h3>What should I do if I forget my password?</h3>
            <p class="faq-answer">If you forget your password, use the 'Recover Password' feature on the login page.
                Enter your registered e-mail address, and you'll receive instructions to reset your password.</p>
        </div>

        <div class="faq-item">
            <h3>How do I report inappropriate content or users?</h3>
            <p class="faq-answer">If you encounter inappropriate content or users, you can report them using the
                'Report' button located near the content or user profile. Your report will help maintain the integrity
                and quality of our community.</p>
        </div>

        <div class="faq-item">
            <h3>How can I delete my account?</h3>
            <p class="faq-answer">To delete your account, please go to your profile settings and choose the 'Delete
                Account' option. Be aware that this action is final and irreversible. Once your account is deleted, you
                will permanently lose access to it on our platform. However, it's important to note that while your
                account is removed, the content you've created will remain on the website. For privacy and data
                integrity, all such content will be anonymized, ensuring that no sources or references trace back to
                your account.</p>
        </div>

        <div class="faq-item">
            <h3>Where can I get more help?</h3>
            <p class="faq-answer">If you need further assistance or have any questions, please visit our <a
                    href="{{ url('/contact') }}">Contact Page</a>. You can use the form provided there to send us your
                issues or questions, and our team will get back to you as soon as possible.</p>
        </div>
    </div>

</div>
@endsection
