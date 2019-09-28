$(document).ready(function() {
    let paxCounter = 0;
    let refreshBookingData = function() {
        $(".removeBookingRowButton").unbind().click(function(e) {
            let self = $(this);
            self.closest(".row").remove();
            refreshBookingData();
        });

        $(".childCheckbox, .smallHeadsetCheckbox").unbind().change(function(e) {
            refreshBookingData();
        });

        $(".firstname").unbind().keydown(function( event ) {
            let self = $(this);

            if ( event.which == 13 ) {
                event.preventDefault();
                self.closest(".paxRow").find('.lastname').focus();
            }
        });

        let numberPax = $(".paxRow").length;
        let numberChildren = $(".childCheckbox:checked").length;
        let numberAdults = numberPax-numberChildren;
        let numberSmallHeadsets = $(".smallHeadsetCheckbox:checked").length;

        let price = (numberAdults*priceAdult)+(numberChildren*priceChild);

        let paxLine = "";
        let adultLine = "";
        let childLine = "";

        if (numberPax === 0) {
            paxLine = "No Passengers";
        } else if (numberPax === 1) {
            paxLine = "1 Passenger";
        } else {
            paxLine = numberPax+" Passengers";
        }

        if (numberPax !== numberAdults) {
            if (numberAdults === 0) {
                adultLine = "<span class=\"text-danger\">No Adults</span>";
            } else if (numberAdults === 1) {
                adultLine = "1 Adult";
            } else {
                adultLine = numberAdults+" Adults";
            }
            if (numberChildren === 1) {
                childLine = "1 Child";
            } else {
                childLine = numberChildren+" Children";
            }

            paxLine += " ("+adultLine+", "+childLine+")"
        }

        if (numberSmallHeadsets === 0) {
            paxLine += "<br />No special Headsets needed";
        } else if (numberSmallHeadsets === 1) {
            paxLine += "<br /><span class=\"text-info\">1 small Headset needed</span>";
        } else {
            paxLine += "<br /><span class=\"text-info\">"+numberSmallHeadsets+" small Headsets needed</span>";
        }

        $("#price").text(price);
        $("#paxInfo").html(paxLine);

        if(numberPax > 0) {
            $("#createBookingButton").removeAttr("disabled");
        } else {
            $("#createBookingButton").attr("disabled", "disabled");
        }
    };

    $("#addPaxButton").click(function(e) {
        $("#paxRows").append(`<div class="row paxRow">
            <div class="col-md-4 pr-1">
                <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" name="pax[`+paxCounter+`][firstname]" class="form-control firstname" placeholder="John" />
                </div>
            </div>
            <div class="col-md-4 pl-1">
                <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" name="pax[`+paxCounter+`][lastname]" class="form-control lastname" placeholder="Doe" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Specialties</label>
                    <div>
                        <input type="checkbox" class="childCheckbox" name="pax[`+paxCounter+`][child]" value="yes" /> Child
                    </div>
                    <div>
                        <input type="checkbox" class="smallHeadsetCheckbox" name="pax[`+paxCounter+`][small_headset]" value="yes" /> Small Headset
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button type="button" style="margin: 27px 0 0 -20px;" class="btn removeBookingRowButton btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>`);
        paxCounter++;
        refreshBookingData();
    });
});
