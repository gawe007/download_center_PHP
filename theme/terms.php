<?php
include("header-public.php");
?>
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">Terms and Conditions</h3>
    </div>
    <div class="card-body">
      <div class="accordion" id="termsAccordion">
        <!-- Acceptance of Terms -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#term1" aria-expanded="true" aria-controls="term1">
              1. Acceptance of Terms
            </button>
          </h2>
          <div id="term1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              By using this platform, you agree to abide by these terms and conditions. If you disagree, you should not use the download center.
            </div>
          </div>
        </div>

        <!-- Use of Downloaded Materials -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term2" aria-expanded="false" aria-controls="term2">
              2. Use of Downloaded Materials
            </button>
          </h2>
          <div id="term2" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              Files are provided for informational and educational use only. Redistribution, resale, or modification is prohibited unless explicitly allowed.
            </div>
          </div>
        </div>

        <!-- Content Ownership -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term3" aria-expanded="false" aria-controls="term3">
              3. Content Ownership & Licensing
            </button>
          </h2>
          <div id="term3" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              All uploaded files remain the intellectual property of their respective owners. Licensing terms must be respected by users.
            </div>
          </div>
        </div>

        <!-- User Conduct -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term4" aria-expanded="false" aria-controls="term4">
              4. User Conduct
            </button>
          </h2>
          <div id="term4" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              Users must not upload harmful, illegal, or infringing content. Abuse of server resources or data scraping is prohibited.
            </div>
          </div>
        </div>

        <!-- File Integrity -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term5" aria-expanded="false" aria-controls="term5">
              5. File Integrity & Security
            </button>
          </h2>
          <div id="term5" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              While the platform strives to provide virus-free files, users should scan downloads independently. The platform is not liable for damages.
            </div>
          </div>
        </div>

        <!-- Limitation of Liability -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSix">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term6" aria-expanded="false" aria-controls="term6">
              6. Limitation of Liability
            </button>
          </h2>
          <div id="term6" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              Use of files is at your own risk. The platform assumes no responsibility for consequences or loss due to file usage.
            </div>
          </div>
        </div>

        <!-- Privacy -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSeven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term7" aria-expanded="false" aria-controls="term7">
              7. Privacy and Data Collection
            </button>
          </h2>
          <div id="term7" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              Some data may be collected for operational and analytics purposes. Please refer to our <a href="#">Privacy Policy</a> for details.
            </div>
          </div>
        </div>

        <!-- Modification of Terms -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingEight">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term8" aria-expanded="false" aria-controls="term8">
              8. Modification of Terms
            </button>
          </h2>
          <div id="term8" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              These terms may be modified at any time without notice. Continued use after changes implies acceptance.
            </div>
          </div>
        </div>

        <!-- Contact Information -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingNine">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term9" aria-expanded="false" aria-controls="term9">
              9. Contact Information
            </button>
          </h2>
          <div id="term9" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#termsAccordion">
            <div class="accordion-body">
              For inquiries or requests, please contact us at: 
              <b>IT Maintenance<b>
            <p class="text-wrap">Lt.4 - Fak. Teknik Universitas Pancasila</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include("footer-public.php");
?>