import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-fallecimiento-bienestar',
  templateUrl: './fallecimiento-bienestar.component.html',
  styleUrls: ['./fallecimiento-bienestar.component.css']
})
export class FallecimientoBienestarComponent implements OnInit {

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';

  constructor() {
  }

ngOnInit() {
}

}
