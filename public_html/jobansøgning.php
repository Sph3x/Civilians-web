<?php
$title = "CN | Jobansøgning";
require_once "assets/incl/recaptchalib.php";
require_once "assets/incl/init.php";
$vrp = new vrp();
$valid = false;
$post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

if(!empty($_POST)) {
    $secret = "6LdyvIwUAAAAACK85bAfcQ9bH7hZ0v8O6MtMsdbzr";
// empty response
    $response = null;

// check secret key
    $reCaptcha = new ReCaptcha($secret);

    if (isset($_POST["g-recaptcha-response"])) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }

    if ($response != null && $response->success) {
        if(!empty($post['id']) && !empty($post['kname']) && !empty($post['alder']) && !empty($post['erf']) && !empty($post['why']) && !empty($post['message'])) {
            $user = $vrp->getUser($post['id']);
            if ($user != false) {
                $discord = $user['discord'];
                if ($discord) {
                    $localip = $vrp->getLocalIP();
                    if($localip == $user['last_login']) {
                        $notify = new notify();
                        if($post["choice"] == "EMS") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Politi") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Mekaniker") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Advokat") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Bilforhandler") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Journalist") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Ejendomsmælger") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }elseif($post["choice"] == "Psykolog") {
                            $notify->webhook = "https://discordapp.com/api/webhooks/";
                        }
                        $notify->data = [
                            [
                                "name" => "Discord:",
                                "value" => ($discord) ? "<@".$discord.">" : "Ingen discord fundet"
                            ],
                            [
                                "name" => "User ID:",
                                "value" => $post['id']
                            ],
                            [
                                "name" => "Karakter navn:",
                                "value" => $post['kname']
                            ],
                            [
                                "name" => "Alder:",
                                "value" => $post['alder']
                            ],
                            [
                                "name" => "Hvad har du af erfaring?:",
                                "value" => $post['erf']
                            ],
                            [
                                "name" => "Hvorfor skal vi vælge dig?",
                                "value" => $post['why']
                            ],
                            [
                                "name" => "Hvad kan du biddrage med?:",
                                "value" => $post['message']
                            ]
                        ];
                        $notify->username = "CiviliansNetwork - ".$post["choice"];
                        $notify->content = "Der er kommet en ny ansøgning!";
                        $notify->footer = date("d/m/y - H:i");
                        $notify->sendNotify();
                    }else{
                        $valid = "Brugeren IP matcher ikke med din! (Tilslutte serveren og prøv igen!)";
                    }
                }else{
                    $valid = "Kan ikke finde din discord! (Tilslutte serveren og prøv igen!)";
                }
            }else{
                $valid = "Fejl user id: ".$post['userid']." findes ikke!";
            }
        } else {
            $valid = "Du mangler og udfylde nogen felter!";
        }
    } else {
        $valid = "Du skal udfylde reCAPTCHA";
    }
}
?>

<?php include 'assets/incl/header.php' ?>

<section id="contact2">
    <div class="container2">
    </div>
    <form action="" method="post">
        <h1>Jobansøgninger</h1>

        <div class="contentform">
            <div class="text-danger"><?php if (!empty($_POST) && $valid) {echo $valid;} ?></div>
            <div class="text-success"><?php if (!empty($_POST) && !$valid) {echo "Anmoding sendt!";} ?></div>
            <div class="topxx">

                <p>Angiv et job</p>
                <div class="custom-select" style="width:330px; color: black;">
                    <select name="choice" size="1" required>
                        <option value="" disabled selected hidden>&zwnj;</option>
                        <option value="EMS">EMS</option>
                        <option value="Politi">Politi</option>
                        <option value="Mekaniker">Mekaniker</option>
                        <option value="Advokat">Advokat</option>
                        <option value="Bilforhandler">Bilforhandler</option>
                        <option value="Journalist">Journalist</option>
                        <option value="Ejendomsmælger">Ejendomsmægler</option>
                        <option value="Psykolog">Psykolog</option>
                    </select>

                </div>

                <div class="form-group">
                    <p>Ingame ID</p>
                    </span>
                    <input type="number" name="id" id="id" data-rule="required" value="<?php if(!empty($post['id']) && $valid) {echo $post['id'];}?>" data-msg="" required/>
                    <div class="validation"></div>
                </div>

                <div class="form-group">
                    <p>Karakter navn</p>
                    </span>
                    <input type="text" name="kname" id="email" value="<?php if(!empty($post['kname']) && $valid) {echo $post['kname'];}?>" data-msg="" required/>
                    <div class="validation"></div>
                </div>

                <div class="form-group">
                    <p>IRL Alder</p>
                    </span>
                    <input type="number" name="alder" id="subject" data-rule="required" value="<?php if(!empty($post['alder']) && $valid) {echo $post['alder'];}?>" data-msg="" required/>
                    <div class="validation"></div>
                </div>
                </div>

                <div class="form-group">
                    <p>Hvad har du af erfaring?</p>
                    </i></span>
                    <textarea type="text" name="erf" id="erf" data-rule="required" data-msg="" required><?php if(!empty($post['erf']) && $valid) {echo $post['erf'];}?></textarea>
                    <div class="validation"></div>
                </div>

                <div class="form-group">
                    <p>Hvorfor skal vi vælge dig?</p>
                    </i></span>
                    <textarea type="text" name="why" id="why" data-rule="required" data-msg="" required><?php if(!empty($post['why']) && $valid) {echo $post['why'];}?></textarea>
                    <div class="validation"></div>
                </div>


                <div class="form-group">
                    <p>Hvad kan du biddrage med?</p>
                    </i></span>
                    <textarea name="message" rows="2" data-rule="required" data-msg="" required><?php if(!empty($post['message']) && $valid) {echo $post['message'];}?></textarea>
                    <div class="validation"></div>
                </div>

                <div>  &zwnj; </div>
<div class="captcha">
                <div class="g-recaptcha" data-sitekey="6LdyvIwUAAAAAD2eEdve2WPCRUF-2kJAbrZMrrf3"></div>
            </div>
</div>
        <button type="submit" class="bouton-contact" value="Submit">Send</button>
        <script>
            var x, i, j, selElmnt, a, b, c;
            x = document.getElementsByClassName("custom-select");
            for (i = 0; i < x.length; i++) {
                selElmnt = x[i].getElementsByTagName("select")[0];
                a = document.createElement("DIV");
                a.setAttribute("class", "select-selected");
                a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
                x[i].appendChild(a);
                b = document.createElement("DIV");
                b.setAttribute("class", "select-items select-hide");
                for (j = 1; j < selElmnt.length; j++) {

                    c = document.createElement("DIV");
                    c.innerHTML = selElmnt.options[j].innerHTML;
                    c.addEventListener("click", function(e) {

                        var y, i, k, s, h;
                        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                        h = this.parentNode.previousSibling;
                        for (i = 0; i < s.length; i++) {
                            if (s.options[i].innerHTML == this.innerHTML) {
                                s.selectedIndex = i;
                                h.innerHTML = this.innerHTML;
                                y = this.parentNode.getElementsByClassName("same-as-selected");
                                for (k = 0; k < y.length; k++) {
                                    y[k].removeAttribute("class");
                                }
                                this.setAttribute("class", "same-as-selected");
                                break;
                            }
                        }
                        h.click();
                    });
                    b.appendChild(c);
                }
                x[i].appendChild(b);
                a.addEventListener("click", function(e) {

                    e.stopPropagation();
                    closeAllSelect(this);
                    this.nextSibling.classList.toggle("select-hide");
                    this.classList.toggle("select-arrow-active");
                });
            }
            function closeAllSelect(elmnt) {

                var x, y, i, arrNo = [];
                x = document.getElementsByClassName("select-items");
                y = document.getElementsByClassName("select-selected");
                for (i = 0; i < y.length; i++) {
                    if (elmnt == y[i]) {
                        arrNo.push(i)
                    } else {
                        y[i].classList.remove("select-arrow-active");
                    }
                }
                for (i = 0; i < x.length; i++) {
                    if (arrNo.indexOf(i)) {
                        x[i].classList.add("select-hide");
                    }
                }
            }

            document.addEventListener("click", closeAllSelect);
        </script>

    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js" integrity="sha256-dHf/YjH1A4tewEsKUSmNnV05DDbfGN3g7NMq86xgGh8=" crossorigin="anonymous"></script>
    <script src="contact.js"></script>
    <section>
        <?php  ?>
        </div>
        </div>
        <script src="content/js/jquery-3.2.1.min.js"></script>
        <script src="content/js/bootstrap.js"></script>
        <div id="containerx">
            <div id="mainx">
            </div>
        </div>
    </section>
</body>

</html>