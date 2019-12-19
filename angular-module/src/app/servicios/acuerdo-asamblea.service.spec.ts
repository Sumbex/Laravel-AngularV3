import { TestBed } from '@angular/core/testing';

import { AcuerdoAsambleaService } from './acuerdo-asamblea.service';

describe('AcuerdoAsambleaService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: AcuerdoAsambleaService = TestBed.get(AcuerdoAsambleaService);
    expect(service).toBeTruthy();
  });
});
