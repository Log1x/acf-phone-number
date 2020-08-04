import intlTelInput from 'intl-tel-input'

/**
 * Load Events
 */
;(function($) {
  if (typeof acf.add_action === 'undefined') {
    return
  }

  /**
   * Ready Append
   *
   * These are 2 events which are fired during the page load.
   * ready = on page load similar to $(document).ready()
   * append = on new DOM elements appended via repeater field
   *
   * @param	 element jQuery element which contains the ACF fields
   * @return void
   */
  acf.add_action('ready append', function(element) {
    acf
      .get_fields(
        {
          type: 'phone_number'
        },
        element
      )
      .each(function() {
        let phone = $(this).find('[name$="[number]"]')
        let country = $(this).find('[name$="[country]"]')

        let tel = intlTelInput(phone.get(0), {
          utilsScript:
            'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.min.js',
          initialCountry: 'US',
          preferredCountries: ['US'],
          formatOnDisplay: true,
          nationalMode: false
        })

        if (!country.val()) {
          country.val(tel.getSelectedCountryData().iso2)
        }

        phone.on('countrychange', function() {
          country.val(tel.getSelectedCountryData().iso2)
        })

        phone.on('keyup change', function() {
          if (typeof intlTelInputUtils === 'undefined') {
            return
          }

          let value = tel.getNumber(intlTelInputUtils.numberFormat.E164)

          if (typeof value !== 'string') {
            return
          }

          tel.setNumber(value)
        })
      })
  })
})(jQuery)
