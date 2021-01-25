<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>WYI Center</title>
{{-- <link rel="preconnect" href="https://fonts.gstatic.com"> --}}
{{-- <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"> --}}
<style>
    .w30{
        width: 30px !important;
    }.bold{
        font-weight: bold;
    }.CANCELLED{
        color: red;
    }
     .table tbody tr,.table tbody tr td {
        border: 1px solid black;
        padding:5px;
    }tr.border-solid td{
        border-bottom: 1px solid black;
    }

</style>
</head>
<body>
    @php
        $i = 1;
        $fecha = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAA3QAAAN0BcFOiBwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAFMSURBVFiF7ZexasMwEIa/S03GPoJjyJLOpmMh9Dmy9zX0KH4Cv0Eh0NFzpyxtHyFZSqBcB0nEdV1i15a1+IcD+SzuP939khCqStOAPaDAvu1/H7sWa0FkJH4gIg/APXALZM6diYgZyNGMdQQqVX3xE5ZAiS3TlFYCyxvAAE8DV/kf3AECcHAZvQJpaBECqeNS4LAA1i6jUlU/Qi/bcZTuc534MgBftXkFNvO3ETjbYnkuSWiBqhYjEHeKJdheALwzzoq7IANWzQSioN6CKBWAy8Fghm65HlvTeN7od8GvBERkIyKFs00IXxM/WgBsa75tIJ/xvrZz4BMrSD8O4fu7ArMIZxEyi3BiBBdhFwQV4bUWTCHCfhWIIcKTSyTtmvEI8FynBKiAR2AnInApYSisgJ0bVwA5cGb6p9kZyH1PcuAZ+3AMTXx0XLmq8g3IuBhLVMzSQAAAAABJRU5ErkJggg==';
        $tecnico = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAA3QAAAN0BcFOiBwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAL/SURBVFiF7dfPb1VVEAfwz2n6iya1ZVFoQpSYSiSUBqMk6sIlEDeEuMHA0uiSBSv/BFT+ABNxYyLKxj1JdQl1oenCSFWkqV3wQ4NtmthqkXFx59XHy323LTEQjZOc3HlnZr7zPXPmnnteiQiPU3oea/b/CTwsgVLKmVLKbCllLsdsKeXMw2D1bjNxD17H23gNa2kaxGellF/waUTc3zLmVt6CUsoY3sCbWMUfEfF8h8/X6McOfIAPI+LnTcEjouvAGD7KpJ9gN57DtRrfa2kbT9/VjB1rytG1B0opE7iSP9/BUkTcVpV9qCZkCGsRcQtLGQNXEmvrFcBhfI9T+fs4plPfizs1MXewN/VpHE/9VGIdrs1VA3QMt3Gobe4AFlLfhZWauBXsSn0BB9pshxLzWCMBjORKTnbMD2Bd1e1P4F4NgXtpG0zfgQ77ycQeaSJwHue6bMsCJtGHQH+brT/n+tJnoQvGOZyvJYAJzKOvS/A0TrStdqTNNtKqCk60+qUGoy9zTNS9Be/iQkSs17erH7Av9TXV+96SHf4+lPbheh1AYl/IXMiTsJQyjlfxVpfkVO/5U6mv1hBYTX03vm3AeR+LpZTxiLjVKs0RXNrkUOrFaOqLmGyzTWIx9VH0boJ1CUfat+Ag5hpYw584uoUKHE3fJpnLnBsEplTN0SQDuFhKGexGIG0X07dJ5jPnAxW40RQREWuqk3ENy9jTZt6D5Q6fJrmRObX25Cb2N+1bxx6exl3M5riL09uI34+bEbFxHyibMO6sxsellGk8nVPzUX2otiMFDxAYLqWMbgPgd22N2xYbEbGcc8OqL+R69sdg+gxrW/Rl1SHyT41VvIInVb3yXpb98xrfy1KZkrejRzFy9VNY68VZfIGeUsoMrmIGX0bEyja2pKvkVryIl/ByPu/j7MadsJTyTIfDQXyHH/Frw4CdDWMCz+KbXNhVzETEdRoupaWUIbygOv93qo7Y9mdLp7qCtQgtdTx/wlcR8Vttnm4EHpX8O/8Z/acI/AVKMQjN2x8lDwAAAABJRU5ErkJggg==';
        $cotizacion = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAA7AAAAOwBeShxvQAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAM3SURBVFiFzddbiFZVFAfw34yjFVZGYxShNKZZkCKkQ8REWE1FFNXDIElkYBRRzltRUVl0J6KitF66KT1UVINGllqSGdqVsEywB32Igi5QSdLFoXpYa5g9X+e7zDiIfzics87eZ63/XnutvdbhEMbMUcjtmDHeBDY1kI/BQCGfhBfGYqSteJ6B5ZiW8nx8XoyXcgdOx/aUj8DJ+DrlH/EwvhoNkS2YV7zbUjy344NCPhZrC3kmVtXIH+LIZoYn5P1EnCncOIiVmIgLsQavY3+h+A3hheOEF9bi8JyzA7/k3L34thGB9rxPwt94RrhzPu7AaTk+HXfi/ByfgvuSdGcaXIFzCt37U29L6MrVX54rWyhcujDHL025O+Ur8azhTLgh5emFznuK71smMCAifDzQEoH2Bu9n4+hxIlMXHRXvThCBtxun4GWxvweFwA7cIgJwG5aK/V+Df/G8kWdHFXZh2VgJ3IX3MRXf4Glx+MzGl3ivQscmfNqqwWYE4B3MwXkiNQfwJP5IQ7Vx0zDPx0LgUdyN1fhLuPPXHKutDweMKgKDYisOCqrScBKewlax5z3jaG8WzlYsvMoDt4ts6E/jD4oTcqLIimb4SWRLiaNEdVyUYzfjCqo9cC5+EDHQj404Q8TDoEjH8tojMmXo+qJG30XYkNfHonj9g+Op9sDOnLARr+DNJHOYKDy1pDen4io8INL5Otyfuvtz7OdyYpfhjqYT69P4VlxfR3kjXIaPcDGuFb3FWbgtF/a/9q0kMIRpovSOFh24F3PxVj4vEAdcvzr1p5ZAH14SgTO1RcN9IsjacJPYmm5RFdep07RWxcBV6BUNyFzRDV2QinsqVtAm9vj7XMR6sdpb8ZiIn0tEwLZEYAkeEUfxYtELdotg6q0g0CeO6U/wuEixRSJ4l+DtKsONCOwVKdeDq/EQfhdZsMDIatiJ7/AcrhFH+ArR2q1MYi2hy3AMzBOl+EZRhF5s8N0T+AyvYh9eE/0ELXZEVR7YLk6+XrH/mxt8vxyT8ac4O35rZrAeJuPdsX5cB6twarNJQx7YJ9y+2vDfzYGgS7Tlu5pNrG2v5oh/gWZtVzPsNvK37tDFf4d5spDFlREiAAAAAElFTkSuQmCC';
        $llegada_tecnico = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAB2QAAAdkBKBtElgAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAMtSURBVFiFtZdbiI1RFMd/39pnmlHEPLiMy7i8SGQm99wepgnJIBEJjSf3vCiKB6E0HqYkRHnw4pIpl/HgzcRQKEJkCCMZyW0Q5sJ8HvY6zj6f75zzfTPHqt1Xa6//+v/32nuvs49HPBsCsgj8BeCNBIap/zX4L8Grh65LwJuYeXPaQJAjIB0gvjPe63B9HSCHgQF54k5UgnzU5B9AaoDpQKETVAjMADngxH6EREUPyc0akE6QLpBaoF8EUDHIQcV0glndXfZZIO0gbWCWh6kDSnRIyPRKxbcDM+OS9wF5a0tp1gTmykHOgHxz9v0ryGlgfEDEWp1vAXrH4Jc9CjwR8G8H+Z0iNTft+CvmF8i2AOakzu2Oyl6kK/oBDHESbdVE30DWk34Ii0A2OkK2OHPDbC75YuNymqnSJKcc5wh7FuQ7MCULeKqS/QSGO+LP6nbODwJCDo8/x369eidsM3bF+4HbWQTcAmqwFdmUcnuXNPfcLFg3WHxgnCPgofoGR0gwVCt43/GVWZ93IQLe3Fay/o6AVpDPEcCZ4vvrFvxTvZAtoF2/fR1fG/YAeVHYsdvV5viKnTy5BPjN9mtGO77HKmBSBAGTbaz/KOVK5vJfRhDADQ2uTLm8Ov3uzM3v7UrHgHOwG3PjYZD2/xZsuwUoBHmuh2sv4VvhgezTmGek+oTYXNKJbdtRzKu3iRJOFSi3zUR8MNfALAVG2GGWgbmu5K2kteTEPL0B5yOSA5glCroSmBgLci/w+++Ou8CYwGKuquiqGAIoAHllf1IpD8wJmIUgx8E02CHHlCB4pqapsBdAIo4AQNZpFepyx2Yy77Kuvro76AKrXHxgdnx4okJX/5T4q0+aqdYV3CH8ymYEgjxQ7IpukoMlTbZmUx0DtiF1WyJ1z6w2UR8hH4j20i0B+WQfJ5T1lFxNjoa8ETKYd1Fja/NEDtg3YrOWdXHmMLNKyZuAXvkUgJ7qLlteSkMCRmmn/A3MyjN50qRWq9BA+tUqSh1W2fufyAEoANOoRIeSqsA75wgzWfB5sVKQdypiR6oq8oZoT7a82GTS/5i0kr8rF0tEE8gTYEJ3k/wB3nHYzMi1X+cAAAAASUVORK5CYII=';
        $calificacion = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAATAAAAEwBzjPcugAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAN7SURBVFiFvddbiFZVFAfw33Iap2zQTHMQUaLEkCyHYAQDhVAUlIICFQmzEJOKnoRefLLe6kl6inqI8gIRJIIPpl0QiiCQGCZMDEymsLt5yYjE3cNZQ8eP+Wa+85Ut2Jy9/2ud/1pn773W3idKKbqRiHgEK3N4vJRyqBueKV04nhYRR3AAt2U7EBFHImJa4whKKY0a9uBDzKhhMxLb05ivofPAJawfR7c+dXEjA5iHK+jD0XzW+1cwrwnnTQ1X7ByKavNdyn0g+ytTd64JYTTNgoh4DfOxBRsTfgdvY7SUsqMJX+MswE704xTuznYqsZ1NyRrPAETEFDyOBxP6FPtKKdeacnVTB2ZiDRZgINsCrEndjQsgIp7CaezCbLybbXZip9Omc+kw/QZwGF9iqIYvwqLaeChtDmPgP6kDquJzDK+jr0V3CIdasL60PaaDotRJADtwAr0teD8uZ+tv0fXmOzsm458wCyJiACN4qJQyEhGDeDLV8zAn+z/iu+y/WUr5IiKW4CMsKaX80NUeUBWag7XxEoziN+zG/dl2JzaaDsfsD2LjRD4my4JlOZVjwY5gKT7G6lLKcCllGKsTW5o2Y3IiOdrKZAEM1QPIIH7FPoiIbRGxLVX7UleXE8nRXiZZgjMYHAffrzqARrJdwv5x7AZx5t8sQbTBV2Bvft1Q9lc05KiUk2TB+6qD5oMW1a34vQNsFe4ppazpNoDFeAFz2xpNLOfwcinlZFcB/B9y3R6IiP6IOBkRc9q90GK/MSL2dmg7J7n7r1O07NoNqmvV9g4PqcP4U+2GPIHt9uTecF0pVtX0sahexZ04jycSu1JKuVj7kjvQg1tUKfgV3sB7afJLKeWvtJ2OsX+FtzAT3+D5xC7DcEZ2EWdVle5rXEj8bO0rHkjsqqr07s1ZO48/Uvdizf5sYheSc2liFxMfprpgHscnmJsvzlJdtT/HwpapfAw/YXcNW43v8YraqYmFyXEUsxKbm76OY/6YYQ8+wzO4HZtVF4veNut5L67llPbjCHa1se1Nrs3J/Wz66qlXwh4sxlr8jE0Zfbt/vftUx+8xfIupiY0n05JrU3KvTV89/JOGqzBdtbEWq9Z4Kta1IX1UdU07gIdxF9ZFRN84tuuS62py35y+VkH90HhO7QqFrVjeZlqfxrLaeCZeMs6SYTm21saRvgZLKf4GFVXNMfWySUMAAAAASUVORK5CYII=';
        $direccion = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAA7AAAAOwBeShxvQAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAKBSURBVFiFvZdJaBRBFIa/0UlA40YmcT2ICGZRJGe9RAmIqLkKXvXidvCmF8mCBBEvnlVQECWIF0EUxYPiRcRkXDFul2DiAsEWdNQRPVQ1ef2mu7q64+SHx3RV/e/9r6fqVVUXyI4uYDuwBlhh+yaA98ANoJwjZirmAvuAt8DfFHsD7LU+/wUbgGcewtqeAutnKr4TCBIEPgCPrE0kcL4CO/KKbwYqMQH7gHUx/DagPybhCrApq/gq4KMKdAto9fBdCtxWvpPAyiwJnFcBrgBFxSkAx60V1FgRGFYxzvqKdwJV4TgCzIsROCc4l4AGxZkPPBGcKtDhk8CQynyrGm/C1LtecHeAhYrbrTgnfBJ4IRweq7Fm4EGMeGgPqV0no2L8eZr4IhWwT4ytBl46xOVGtFb4DajxBa4E2hR5t+1vBcY9xEMbB1qs7x41FinhOSqBFtWeEs8jqp2EKaJT98WloRP4ptol+/sZ2IXfQVMGeoWwfqnAlcAY8Fu02x1ClzHlWMTsE0mQpfcLeO1KoALcF+1eR+Ax4I+1Vw6ejHEP+OlKAOCieO4CehzB07AF2CjaFzQhLoGrRNfCaaZ3QtkfJDyHnCbgjOJcc+c7jVNES2cYM9clzD/SDTQKfqPt67GcBvsiMsZJX3GAJZiVr7faZR6+y4G7yvcTsDhLAgCHqd1gAszOFlcdHcAgZgq038Gs4mD+xnJMMHnGhzeiSQdvlNqj3BudwHdH8DT7QbQKciFuKnzt0EzFwdx2rucQv0ntTSk3Spgt1Ff8HX73x0xox5x0aeIB5luiLtiGOaySxKuYb4m64oAjgf31Fg9xJEb86GyJhxgU4gOzLR7imLXc+Ac1F0Q4yBZK6QAAAABJRU5ErkJggg==';
    @endphp
    <header>
        <img src="{{$header}}" width="100px" alt="">
    </header>
    <br>
    <main>
        <table class="table">
            <tbody>
                <tr>
                    <td><img src="{{ $fecha }}" width="15" height="15" alt=""></td>
                    <td>Fecha del Servicio</td>
                    <td><img src="{{ $tecnico }}" width="15" height="15" alt=""></td>
                    <td>Orden con Tecnico</td>
                    <td><img src="{{ $llegada_tecnico }}" width="15" height="15" alt=""></td>
                    <td>LLegada del técnico</td>
                </tr>
                <tr>
                    <td><img src="{{ $cotizacion }}" width="15" height="15" alt=""></td>
                    <td>Orden con cotización</td>
                    <td><img src="{{ $calificacion }}" width="15" height="15" alt=""></td>
                    <td>Orden calificada</td>
                    <td><img src="{{ $direccion }}" width="15" height="15" alt=""></td>
                    <td>¿Tiene dirección?</td>
                </tr>


            </tbody>
        </table>
        <table class="items">

            <thead>
                <tr id="0" class="border-solid">
                    <td align="center" class="bold">#</td>
                    <td align="center" class="bold">Cliente</td>
                    <td align="center"><img src="{{ $fecha }}" width="15" height="15" alt=""></td>
                    <td align="center"><img src={{ $tecnico }}  width="15" height="15" /></td>
                    <td align="center"><img src={{ $llegada_tecnico }}  width="15" height="15" /></td>
                    <td align="center"><img src={{ $cotizacion }}  width="15" height="15" /></td>
                    <td align="center"><img src={{ $calificacion }}  width="15" height="15" /></td>
                    <td align="center"><img src={{ $direccion }}  width="15" height="15" /></td>
                </tr>
            </thead>
            <tbody>

                @forelse(json_decode($ordenes) as $orden)
                <tr class="border-solid">
                        <td class="w30">{{ $i++ }}</td>
                        <td class="{{ ($orden->state == 'CANCELLED') ? 'CANCELLED' : '' }}">{{ $orden->user->name }} {{ (strlen($orden->user->lastName) > 10) ? substr($orden->user->lastName,0,10).'...' :  $orden->user->lastName}}</td>
                        <td align="center">{{ substr($orden->service_date,0,10) }}</td>
                        <td align="center">{{ ($orden->fixerman_user != null) ? "Si" : "No" }}</td>
                        <td align="center">{{ ($orden->fixerman_arrive == 'SI') ? "Si" : "No" }}</td>
                        <td align="center">{{ ($orden->quotations != null) ? "Si" : "No" }}</td>
                        <td align="center">{{ ($orden->state == 'QUALIFIED') ? "Si" : "No" }}</td>
                        <td align="center">{{ ($orden->address == null) ? "No" : "Si" }}</td>
                </tr>
                @empty
                <p>geen invoices gevonden</p>
                @endforelse
            </tbody>

        </table>
    </main>
</body>
</html>
