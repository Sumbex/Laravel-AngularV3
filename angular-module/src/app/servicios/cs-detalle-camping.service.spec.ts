import { TestBed } from '@angular/core/testing';

import { CsDetalleCampingService } from './cs-detalle-camping.service';

describe('CsDetalleCampingService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: CsDetalleCampingService = TestBed.get(CsDetalleCampingService);
    expect(service).toBeTruthy();
  });
});
