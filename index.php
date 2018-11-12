<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Test</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  </head>
  <body>

    <div class="container p-5">

      <div id="resultCard" class="card m-5" hidden>
        <div id="resultOutput" class="card-body"></div>
      </div>

      <form>
        <div class="form-group">
          <label for="nameInput">ФИО</label>
          <input type="text" class="form-control" id="nameInput" placeholder="Введите ФИО">
          <small id="nameError" class="form-text text-danger" hidden>Проверьте поле.</small>
        </div>
        <div class="form-group">
          <label for="telephoneInput">Телефон</label>
          <input type="tel" class="form-control" id="telephoneInput" placeholder="Введите телефон">
          <small id="telephoneError" class="form-text text-danger" hidden>Проверьте поле.</small>
        </div>
        <div class="form-group">
          <label for="addressInput">Адрес</label>
          <input type="text" class="form-control" id="addressInput" placeholder="Введите адрес">
          <small id="addressError" class="form-text text-danger" hidden>Проверьте поле.</small>
        </div>
        <button type="button" onclick="setHello()" class="btn btn-primary">Найти пункт выдачи</button>
      </form>

    </div>

    <script>
    const resultCard = document.getElementById('resultCard');
    const nameInput = document.getElementById('nameInput');
    const telephoneInput = document.getElementById('telephoneInput');
    const addressInput = document.getElementById('addressInput');

    const nameError = document.getElementById('nameError');
    const telephoneError = document.getElementById('telephoneError');
    const addressError = document.getElementById('addressError');

    var telephoneRegex = new RegExp(/^\d{10}$/);


    function checkInput() {

      var result = true;

      if (nameInput.value) {
        nameError.setAttribute('hidden', '');
      } else {
        nameError.removeAttribute('hidden');
        result = false;
      }

      if (telephoneInput.value && telephoneRegex.test(telephoneInput.value)) {
        telephoneError.setAttribute('hidden', '');
      } else {
        telephoneError.removeAttribute('hidden');
        result = false;
      }

      if (addressInput.value) {
        addressError.setAttribute('hidden', '');
      } else {
        addressError.removeAttribute('hidden');
        result = false;
      }

      return result;
    }

    function sendRequest() {
      fetch('ajax.php', {
        method : "POST",
        body : JSON.stringify({
          name: nameInput.value,
          telephone: telephoneInput.value,
          address: addressInput.value
        })
      })
      .then(
          response => response.json()
      )
      .then(
          result => {
            document.getElementById('resultOutput').innerHTML =
            `${result.name} (${result.telephone}): ближайший пункт выдачи ${result.city} находится на расстоянии ${result.distance}км`
            resultCard.removeAttribute('hidden');
          }
      );

    }


    function setHello(){
      resultCard.setAttribute('hidden', '');

      if (checkInput()) {
        sendRequest();
      }
    }
    </script>

  </body>
</html>
